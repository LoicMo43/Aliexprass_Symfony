<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder,
                              array $options): void
    {
        $builder
            ->add('fullname', TextType::class, ['label' => "Nom complet"])
            ->add('compagny',TextType::class, ['label' => "Entreprise"])
            ->add('address', TextType::class, ['label' => "Adresse"])
            ->add('complement', TextType::class, ['label' => "Complément"])
            ->add('phone', TextType::class, ['label' => "Numéro de téléphone"])
            ->add('city', TextType::class, ['label' => "Ville"])
            ->add('codePostal', TextType::class, ['label' => "Code postal"])
            ->add('country', CountryType::class, ['label' => "Pays"]); // CountryType permet de lister tous les pays du monde
    }

    /**
     * @param OptionsResolver $resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'sanitize_html' => true,
            'data_class' => Address::class,
        ]);
    }
}
