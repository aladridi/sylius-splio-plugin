<?php

declare(strict_types=1);

namespace Dridialaa\SyliusSplioPlugin\Menu;

use Sylius\Bundle\AdminBundle\Menu\MainMenuBuilder;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class SplioAdminMenuSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            MainMenuBuilder::EVENT_NAME => 'addSplioConfigurationItem',
        ];
    }

    public function addSplioConfigurationItem(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();
        $configuration = $menu->getChild('configuration');

        if (null === $configuration) {
            return;
        }

        $configuration
            ->addChild('splio_settings', ['route' => 'app_admin_splio_settings'])
            ->setLabel('Splio CRM')
            ->setLabelAttribute('icon', 'tabler:plug-connected')
        ;
    }
}
