<?php

declare(strict_types=1);

namespace Dridialaa\SyliusSplioPlugin\Form\Type\Splio;

use Dridialaa\SyliusSplioPlugin\Entity\Splio\SplioSettings;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Url;

final class SplioSettingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('enabled', CheckboxType::class, [
                'label' => 'Activer la synchronisation Splio',
                'required' => false,
            ])
            ->add('baseUri', TextType::class, [
                'label' => 'URL API Splio',
                'constraints' => [new NotBlank(), new Url()],
            ])
            ->add('authEndpoint', TextType::class, [
                'label' => 'Endpoint OAuth',
                'constraints' => [new NotBlank()],
            ])
            ->add('clientId', TextType::class, [
                'label' => 'Client ID',
                'constraints' => [new NotBlank()],
            ])
            ->add('clientSecret', PasswordType::class, [
                'label' => 'Client secret',
                'always_empty' => false,
                'constraints' => [new NotBlank()],
            ])
            ->add('timeout', IntegerType::class, [
                'label' => 'Timeout HTTP en secondes',
                'constraints' => [new GreaterThanOrEqual(1)],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SplioSettings::class,
        ]);
    }
}
