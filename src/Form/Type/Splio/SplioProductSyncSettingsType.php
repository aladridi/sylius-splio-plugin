<?php

declare(strict_types=1);

namespace Dridialaa\SyliusSplioPlugin\Form\Type\Splio;

use Dridialaa\SyliusSplioPlugin\Entity\Splio\SplioProductSyncSettings;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;

final class SplioProductSyncSettingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('enabled', CheckboxType::class, [
                'label' => 'Activer la synchronisation produits',
                'required' => false,
            ])
            ->add('syncMode', ChoiceType::class, [
                'label' => 'Mode de synchronisation',
                'choices' => [
                    'Manuel' => SplioProductSyncSettings::MODE_MANUAL,
                    'Planifié' => SplioProductSyncSettings::MODE_SCHEDULED,
                    'Temps réel' => SplioProductSyncSettings::MODE_REALTIME,
                ],
            ])
            ->add('frequency', ChoiceType::class, [
                'label' => 'Fréquence',
                'choices' => [
                    'Toutes les 15 minutes' => SplioProductSyncSettings::FREQUENCY_EVERY_15_MINUTES,
                    'Toutes les heures' => SplioProductSyncSettings::FREQUENCY_HOURLY,
                    'Une fois par jour' => SplioProductSyncSettings::FREQUENCY_DAILY,
                ],
            ])
            ->add('productEndpoint', TextType::class, [
                'label' => 'Endpoint API produits',
                'constraints' => [new NotBlank()],
            ])
            ->add('batchSize', IntegerType::class, [
                'label' => 'Taille des lots',
                'constraints' => [
                    new GreaterThanOrEqual(1),
                    new LessThanOrEqual(500),
                ],
            ])
            ->add('includeDisabledProducts', CheckboxType::class, [
                'label' => 'Inclure les produits désactivés',
                'required' => false,
            ])
            ->add('includeVariants', CheckboxType::class, [
                'label' => 'Synchroniser les variantes',
                'required' => false,
            ])
            ->add('syncImages', CheckboxType::class, [
                'label' => 'Synchroniser les images',
                'required' => false,
            ])
            ->add('syncPrices', CheckboxType::class, [
                'label' => 'Synchroniser les prix',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SplioProductSyncSettings::class,
        ]);
    }
}
