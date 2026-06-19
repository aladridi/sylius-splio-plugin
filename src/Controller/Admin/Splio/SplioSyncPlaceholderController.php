<?php

declare(strict_types=1);

namespace Dridialaa\SyliusSplioPlugin\Controller\Admin\Splio;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

final class SplioSyncPlaceholderController extends AbstractController
{
    public function customers(): Response
    {
        return $this->renderSyncPage(
            'Customers',
            'Synchronisation clients vers Splio',
            'La configuration du flux clients sera ajoutée dans une prochaine étape.',
        );
    }

    public function orders(): Response
    {
        return $this->renderSyncPage(
            'Orders',
            'Synchronisation commandes vers Splio',
            'La configuration du flux commandes sera ajoutée dans une prochaine étape.',
        );
    }

    private function renderSyncPage(string $section, string $title, string $description): Response
    {
        return $this->render('@DridialaaSyliusSplioPlugin/admin/splio/sync_placeholder.html.twig', [
            'section' => $section,
            'title' => $title,
            'description' => $description,
        ]);
    }
}
