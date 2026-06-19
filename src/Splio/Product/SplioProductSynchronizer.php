<?php

declare(strict_types=1);

namespace Dridialaa\SyliusSplioPlugin\Splio\Product;

use Doctrine\ORM\EntityManagerInterface;
use Dridialaa\SyliusSplioPlugin\Repository\Splio\SplioProductSyncSettingsRepository;
use Dridialaa\SyliusSplioPlugin\Splio\Api\SplioApiClientInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;

final readonly class SplioProductSynchronizer
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private SplioProductSyncSettingsRepository $settingsRepository,
        private SplioProductPayloadFactory $payloadFactory,
        private SplioApiClientInterface $apiClient,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function synchronize(?int $limit = null, int $offset = 0, bool $dryRun = false, bool $force = false): SplioProductSyncResult
    {
        $settings = $this->settingsRepository->getSettings();

        if (!$settings->isEnabled() && !$force) {
            return new SplioProductSyncResult(0, 0, 0, ['Product synchronization is disabled.']);
        }

        $criteria = $settings->shouldIncludeDisabledProducts() ? [] : ['enabled' => true];
        $products = $this->productRepository->findBy(
            $criteria,
            ['updatedAt' => 'ASC'],
            $limit ?? $settings->getBatchSize(),
            $offset,
        );

        $processed = 0;
        $succeeded = 0;
        $failed = 0;
        $errors = [];

        /** @var ProductInterface $product */
        foreach ($products as $product) {
            ++$processed;
            $payload = $this->payloadFactory->create($product, $settings);

            if ($dryRun) {
                ++$succeeded;
                continue;
            }

            try {
                $this->apiClient->request('PUT', $settings->getProductEndpoint(), $payload);
                ++$succeeded;
            } catch (\Throwable $exception) {
                ++$failed;
                $errors[] = sprintf('%s: %s', (string) $product->getCode(), $exception->getMessage());
            }
        }

        if (!$dryRun && $processed > 0) {
            $settings->markSynced();
            $this->entityManager->flush();
        }

        return new SplioProductSyncResult($processed, $succeeded, $failed, $errors);
    }
}
