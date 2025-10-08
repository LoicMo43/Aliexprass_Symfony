import React, { useMemo, useState } from "react";
import { createRoot } from "react-dom/client";

const Alert = ({ tone = "info", children }) => (
  <div className={`neo-auth__alert neo-auth__alert--${tone}`}>{children}</div>
);

const LoginHero = ({ title, subtitle }) => (
  <div className="neo-auth__hero">
    <h1 className="neo-auth__title">{title}</h1>
    <p className="neo-auth__subtitle">{subtitle}</p>
  </div>
);

const LoginForm = ({
  action,
  csrfToken,
  lastUsername,
  errorMessage,
  showAuthenticatedNotice,
  username,
  forgotPasswordUrl,
  registerUrl,
  logoutUrl
}) => {
  const [email, setEmail] = useState(lastUsername || "");
  const [passwordVisible, setPasswordVisible] = useState(false);

  const passwordInputType = passwordVisible ? "text" : "password";
  const passwordToggleLabel = passwordVisible
    ? "Masquer le mot de passe"
    : "Afficher le mot de passe";

  return (
    <form method="post" action={action} className="neo-auth__form">
      {showAuthenticatedNotice ? (
        <Alert tone="success">
          Vous êtes déjà connecté(e) en tant que <strong>{username}</strong>.{" "}
          <a className="neo-auth__link" href={logoutUrl}>
            Se déconnecter
          </a>
        </Alert>
      ) : null}

      {errorMessage ? <Alert tone="error">{errorMessage}</Alert> : null}

      <div className="neo-auth__field">
        <label className="neo-auth__label" htmlFor="login-email">
          Email
        </label>
        <input
          id="login-email"
          name="email"
          type="email"
          autoComplete="email"
          required
          value={email}
          onChange={(event) => setEmail(event.target.value)}
          className="neo-auth__input"
          placeholder="vous@example.com"
        />
      </div>

      <div className="neo-auth__field neo-auth__field--password">
        <label className="neo-auth__label" htmlFor="login-password">
          Mot de passe
        </label>
        <div className="neo-auth__password-wrapper">
          <input
            id="login-password"
            name="password"
            type={passwordInputType}
            autoComplete="current-password"
            required
            className="neo-auth__input neo-auth__input--password"
            placeholder="Votre mot de passe"
          />
          <button
            type="button"
            className="neo-auth__password-toggle"
            onClick={() => setPasswordVisible((prev) => !prev)}
            aria-label={passwordToggleLabel}
          >
            <i
              className={`${
                passwordVisible ? "linearicons-eye" : "linearicons-eye-close"
              }`}
              aria-hidden="true"
            ></i>
          </button>
        </div>
      </div>

      <input type="hidden" name="_csrf_token" value={csrfToken} />

      <div className="neo-auth__footer">
        <label className="neo-auth__checkbox">
          <input type="checkbox" name="_remember_me" />
          <span>Se souvenir de moi</span>
        </label>
        <a className="neo-auth__link" href={forgotPasswordUrl}>
          Mot de passe oublié ?
        </a>
      </div>

      <button type="submit" className="neo-auth__submit">
        Se connecter
      </button>

      <p className="neo-auth__helper">
        Pas encore de compte ?{" "}
        <a className="neo-auth__link" href={registerUrl}>
          Créer un compte
        </a>
      </p>
    </form>
  );
};

const LoginPage = (props) => {
  const {
    title = "Connexion",
    subtitle = "Accédez à votre espace personnel en toute sécurité.",
    action = "/login",
    csrfToken = "",
    lastUsername = "",
    errorMessage = "",
    isAuthenticated = false,
    username = "",
    forgotPasswordUrl = "#",
    registerUrl = "#",
    logoutUrl = "#"
  } = props;

  const introSummary = useMemo(
    () => [
      "Retrouvez vos commandes et vos listes d'envies.",
      "Suivez vos livraisons en temps réel.",
      "Accédez aux offres exclusives membres."
    ],
    []
  );

  return (
    <section className="neo-auth">
      <div className="neo-auth__background" aria-hidden="true" />
      <div className="neo-auth__container">
        <div className="neo-auth__split">
          <div className="neo-auth__panel neo-auth__panel--intro">
            <LoginHero title={title} subtitle={subtitle} />
            <ul className="neo-auth__summary">
              {introSummary.map((entry) => (
                <li key={entry}>
                  <span className="neo-auth__summary-icon">
                    <i className="linearicons-check" aria-hidden="true"></i>
                  </span>
                  <span className="neo-auth__summary-text">{entry}</span>
                </li>
              ))}
            </ul>
          </div>
          <div className="neo-auth__panel neo-auth__panel--form">
            <LoginForm
              action={action}
              csrfToken={csrfToken}
              lastUsername={lastUsername}
              errorMessage={errorMessage}
              showAuthenticatedNotice={isAuthenticated}
              username={username}
              forgotPasswordUrl={forgotPasswordUrl}
              registerUrl={registerUrl}
              logoutUrl={logoutUrl}
            />
          </div>
        </div>
      </div>
    </section>
  );
};

const bootstrap = () => {
  const container = document.getElementById("react-login-root");

  if (!container) {
    return;
  }

  const rawProps = container.dataset.props || "{}";
  let parsedProps = {};

  try {
    parsedProps = JSON.parse(rawProps);
  } catch (error) {
    console.error("Impossible de parser les props de la page login :", error);
  }

  const root = createRoot(container);
  root.render(<LoginPage {...parsedProps} />);
};

if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", bootstrap);
} else {
  bootstrap();
}
