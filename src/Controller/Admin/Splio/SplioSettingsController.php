<?php

declare(strict_types=1);

namespace Dridialaa\SyliusSplioPlugin\Controller\Admin\Splio;

use Dridialaa\SyliusSplioPlugin\Form\Type\Splio\SplioSettingsType;
use Dridialaa\SyliusSplioPlugin\Repository\Splio\SplioSettingsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class SplioSettingsController extends AbstractController
{
    public function __construct(
        private readonly SplioSettingsRepository $settingsRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $settings = $this->settingsRepository->getSettings();
        $form = $this->createForm(SplioSettingsType::class, $settings);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlash('success', 'La configuration Splio a été enregistrée.');

            return $this->redirectToRoute('app_admin_splio_settings');
        }

        return $this->render('@DridialaaSyliusSplioPlugin/admin/splio/settings.html.twig', [
            'form' => $form,
        ]);
    }
}
