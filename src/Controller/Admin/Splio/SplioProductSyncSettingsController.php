<?php

declare(strict_types=1);

namespace Dridialaa\SyliusSplioPlugin\Controller\Admin\Splio;

use Doctrine\ORM\EntityManagerInterface;
use Dridialaa\SyliusSplioPlugin\Form\Type\Splio\SplioProductSyncSettingsType;
use Dridialaa\SyliusSplioPlugin\Repository\Splio\SplioProductSyncLogRepository;
use Dridialaa\SyliusSplioPlugin\Repository\Splio\SplioProductSyncSettingsRepository;
use Dridialaa\SyliusSplioPlugin\Splio\Product\SplioProductSynchronizer;
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

    public function __invoke(Request $request, SplioProductSyncLogRepository $syncLogRepository): Response
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
            'logs' => $syncLogRepository->findLatest(),
        ]);
    }

    public function run(Request $request, SplioProductSynchronizer $productSynchronizer): Response
    {
        if (!$this->isCsrfTokenValid('splio_product_sync_run', (string) $request->request->get('_token'))) {
            $this->addFlash('error', 'Jeton de sécurité invalide.');

            return $this->redirectToRoute('app_admin_splio_product_sync_settings');
        }

        $result = $productSynchronizer->synchronize();

        if ([] !== $result->errors) {
            $this->addFlash('warning', sprintf(
                'Synchronisation produits terminée avec %d succès et %d erreur(s) sur %d produit(s).',
                $result->succeeded,
                $result->failed,
                $result->processed,
            ));

            foreach (array_slice($result->errors, 0, 3) as $error) {
                $this->addFlash('error', $error);
            }

            return $this->redirectToRoute('app_admin_splio_product_sync_settings');
        }

        $this->addFlash('success', sprintf(
            'Synchronisation produits terminée : %d produit(s) synchronisé(s).',
            $result->succeeded,
        ));

        return $this->redirectToRoute('app_admin_splio_product_sync_settings');
    }
}
