<?php

declare(strict_types=1);

namespace Dridialaa\SyliusSplioPlugin\Controller\Admin\Splio;

use Doctrine\ORM\EntityManagerInterface;
use Dridialaa\SyliusSplioPlugin\Form\Type\Splio\SplioProductSyncSettingsType;
use Dridialaa\SyliusSplioPlugin\Repository\Splio\SplioProductSyncSettingsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class SplioProductSyncSettingsController extends AbstractController
{
    public function __construct(
        private readonly SplioProductSyncSettingsRepository $settingsRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $settings = $this->settingsRepository->getSettings();
        $form = $this->createForm(SplioProductSyncSettingsType::class, $settings);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $settings->touch();
            $this->entityManager->flush();
            $this->addFlash('success', 'La synchronisation produits Splio a été enregistrée.');

            return $this->redirectToRoute('app_admin_splio_product_sync_settings');
        }

        return $this->render('@DridialaaSyliusSplioPlugin/admin/splio/product_sync_settings.html.twig', [
            'form' => $form,
            'settings' => $settings,
        ]);
    }
}
