import React, { useMemo } from "react";
import { createRoot } from "react-dom/client";

const Alert = ({ tone = "info", children }) => (
  <div className={`neo-auth__alert neo-auth__alert--${tone}`}>{children}</div>
);

const Hero = ({ title, subtitle }) => (
  <div className="neo-auth__hero">
    <h1 className="neo-auth__title">{title}</h1>
    <p className="neo-auth__subtitle">{subtitle}</p>
  </div>
);

const Field = ({ children }) => <div className="neo-auth__field">{children}</div>;

const FieldRow = ({ children }) => (
  <div className="neo-auth__field-row">{children}</div>
);

const RenderField = ({ field }) => {
  if (!field || field.type === "checkbox") {
    return null;
  }

  const {
    id,
    name,
    type = "text",
    label,
    value,
    placeholder,
    required,
    attributes = {},
    errors = []
  } = field;

  const inputProps = {
    id: id || name,
    name,
    type,
    defaultValue: type === "password" ? "" : value || "",
    placeholder: placeholder || "",
    required,
    className: "neo-auth__input"
  };

  if (attributes && typeof attributes === "object") {
    Object.entries(attributes).forEach(([attrKey, attrValue]) => {
      if (attrValue === undefined || attrValue === null) {
        return;
      }

      const normalizedKey =
        attrKey === "autocomplete"
          ? "autoComplete"
          : attrKey === "maxlength"
          ? "maxLength"
          : attrKey === "minlength"
          ? "minLength"
          : attrKey;

      inputProps[normalizedKey] = attrValue;
    });
  }

  return (
    <Field>
      {label ? (
        <label className="neo-auth__label" htmlFor={inputProps.id}>
          {label}
        </label>
      ) : null}
      <input {...inputProps} />
      {errors?.length ? (
        <p className="neo-auth__field-error">{errors.join(" ")}</p>
      ) : null}
    </Field>
  );
};

const TermsCheckbox = ({ field, termsUrl }) => {
  if (!field) {
    return null;
  }

  const { id, name, label, checked, required, errors = [] } = field;
  const inputId = id || name;

  return (
    <div className="neo-auth__checkbox-group">
      <label className="neo-auth__checkbox neo-auth__checkbox--inline" htmlFor={inputId}>
        <input
          id={inputId}
          name={name}
          type="checkbox"
          defaultChecked={Boolean(checked)}
          required={required}
        />
        <span>
          {label || "J'accepte les conditions"}
          {termsUrl ? (
            <>
              {" "}
              <a className="neo-auth__link" href={termsUrl} target="_blank" rel="noreferrer">
                Lire les conditions
              </a>
            </>
          ) : null}
        </span>
      </label>
      {errors?.length ? (
        <p className="neo-auth__field-error">{errors.join(" ")}</p>
      ) : null}
    </div>
  );
};

const RegisterForm = ({
  action,
  method,
  csrfToken,
  csrfFieldName = "_token",
  fields,
  errors,
  termsUrl,
  loginUrl,
  flashes
}) => {
  const layout = useMemo(() => {
    if (!Array.isArray(fields)) {
      return [];
    }

    const visitedGroups = new Set();

    return fields.reduce((accumulator, field) => {
      if (!field) {
        return accumulator;
      }

      if (field.group) {
        if (visitedGroups.has(field.group)) {
          return accumulator;
        }

        const grouped = fields.filter(
          (candidate) => candidate && candidate.group === field.group
        );

        visitedGroups.add(field.group);
        accumulator.push({ type: "row", key: field.group, fields: grouped });
        return accumulator;
      }

      if (field.type === "checkbox") {
        accumulator.push({
          type: "checkbox",
          key: field.key || field.name || field.id,
          field
        });
        return accumulator;
      }

      accumulator.push({
        type: "field",
        key: field.key || field.name || field.id,
        field
      });
      return accumulator;
    }, []);
  }, [fields]);

  return (
    <form className="neo-auth__form" action={action} method={method || "post"}>
      {csrfToken ? (
        <input type="hidden" name={csrfFieldName || "_token"} value={csrfToken} />
      ) : null}

      {flashes?.length
        ? flashes.map((message, index) => (
            <Alert tone="error" key={`flash-${index}`}>
              {message}
            </Alert>
          ))
        : null}

      {errors?.length
        ? errors.map((message, index) => (
            <Alert tone="error" key={`error-${index}`}>
              {message}
            </Alert>
          ))
        : null}

      {layout.map((item, index) => {
        if (item.type === "row") {
          return (
            <FieldRow key={`row-${item.key || index}`}>
              {item.fields.map((fieldItem, fieldIndex) => (
                <RenderField
                  key={fieldItem.key || fieldItem.name || fieldItem.id || `${item.key}-${fieldIndex}`}
                  field={fieldItem}
                />
              ))}
            </FieldRow>
          );
        }

        if (item.type === "checkbox") {
          return (
            <div className="neo-auth__field" key={item.key || index}>
              <TermsCheckbox field={item.field} termsUrl={termsUrl} />
            </div>
          );
        }

        return (
          <RenderField key={item.key || index} field={item.field} />
        );
      })}

      <button type="submit" className="neo-auth__submit">
        Creer mon compte
      </button>

      <p className="neo-auth__helper">
        Deja membre ?{" "}
        <a className="neo-auth__link" href={loginUrl}>
          Se connecter
        </a>
      </p>
    </form>
  );
};

const RegisterPage = (props) => {
  const {
    title = "Creer un compte",
    subtitle = "Rejoignez la communaute AliExprass et profitez des avantages membres.",
    action = "/register",
    method = "post",
    csrfToken = "",
    csrfFieldName = "_token",
    fields = [],
    errors = [],
    flashes = [],
    termsUrl = "#",
    loginUrl = "#"
  } = props;

  const highlights = useMemo(
    () => [
      "Sauvegardez vos listes d'envies et vos paniers.",
      "Acces prioritaire aux ventes privees.",
      "Gagnez des points fidelite a chaque commande."
    ],
    []
  );

  return (
    <section className="neo-auth neo-auth--register">
      <div className="neo-auth__background" aria-hidden="true" />
      <div className="neo-auth__container">
        <div className="neo-auth__split">
          <div className="neo-auth__panel neo-auth__panel--intro">
            <Hero title={title} subtitle={subtitle} />
            <ul className="neo-auth__summary">
              {highlights.map((line) => (
                <li key={line}>
                  <span className="neo-auth__summary-icon neo-auth__summary-icon--accent">
                    <i className="linearicons-star" aria-hidden="true"></i>
                  </span>
                  <span className="neo-auth__summary-text">{line}</span>
                </li>
              ))}
            </ul>
          </div>
          <div className="neo-auth__panel neo-auth__panel--form">
            <RegisterForm
              action={action}
              method={method}
              csrfToken={csrfToken}
              csrfFieldName={csrfFieldName}
              fields={fields}
              errors={errors}
              flashes={flashes}
              termsUrl={termsUrl}
              loginUrl={loginUrl}
            />
          </div>
        </div>
      </div>
    </section>
  );
};

const bootstrap = () => {
  const container = document.getElementById("react-register-root");

  if (!container) {
    return;
  }

  const rawProps = container.dataset.props || "{}";
  let parsedProps = {};

  try {
    parsedProps = JSON.parse(rawProps);
  } catch (error) {
    console.error("Impossible de parser les props de la page register :", error);
  }

  const root = createRoot(container);
  root.render(<RegisterPage {...parsedProps} />);
};

if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", bootstrap);
} else {
  bootstrap();
}

