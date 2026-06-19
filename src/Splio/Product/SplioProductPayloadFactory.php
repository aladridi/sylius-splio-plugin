<?php

declare(strict_types=1);

namespace Dridialaa\SyliusSplioPlugin\Splio\Product;

use Dridialaa\SyliusSplioPlugin\Entity\Splio\SplioProductSyncSettings;
use Sylius\Component\Core\Model\ChannelPricingInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;

final class SplioProductPayloadFactory
{
    /**
     * @return array<string, mixed>
     */
    public function create(ProductInterface $product, SplioProductSyncSettings $settings): array
    {
        $payload = [
            'external_id' => (string) $product->getCode(),
            'name' => $product->getName(),
            'description' => $product->getDescription(),
            'short_description' => $product->getShortDescription(),
            'slug' => $product->getSlug(),
            'enabled' => $product->isEnabled(),
            'created_at' => $this->formatDate($product->getCreatedAt()),
            'updated_at' => $this->formatDate($product->getUpdatedAt()),
            'main_taxon' => $product->getMainTaxon()?->getName(),
        ];

        if ($settings->shouldSyncImages()) {
            $payload['images'] = $this->mapImages($product);
        }

        if ($settings->shouldIncludeVariants()) {
            $payload['variants'] = $this->mapVariants($product, $settings);
        }

        return array_filter($payload, static fn (mixed $value): bool => null !== $value && [] !== $value);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function mapVariants(ProductInterface $product, SplioProductSyncSettings $settings): array
    {
        $variants = [];

        /** @var ProductVariantInterface $variant */
        foreach ($product->getVariants() as $variant) {
            if (!$settings->shouldIncludeDisabledProducts() && !$variant->isEnabled()) {
                continue;
            }

            $variantPayload = [
                'external_id' => (string) $variant->getCode(),
                'name' => $variant->getName(),
                'enabled' => $variant->isEnabled(),
                'tracked' => $variant->isTracked(),
                'on_hand' => $variant->getOnHand(),
                'created_at' => $this->formatDate($variant->getCreatedAt()),
                'updated_at' => $this->formatDate($variant->getUpdatedAt()),
            ];

            if ($settings->shouldSyncPrices()) {
                $variantPayload['prices'] = $this->mapPrices($variant);
            }

            if ($settings->shouldSyncImages()) {
                $variantPayload['images'] = $this->mapImages($variant);
            }

            $variants[] = array_filter($variantPayload, static fn (mixed $value): bool => null !== $value && [] !== $value);
        }

        return $variants;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function mapPrices(ProductVariantInterface $variant): array
    {
        $prices = [];

        /** @var ChannelPricingInterface $channelPricing */
        foreach ($variant->getChannelPricings() as $channelPricing) {
            $price = $channelPricing->getPrice();

            if (null === $price) {
                continue;
            }

            $prices[] = [
                'channel' => $channelPricing->getChannelCode(),
                'price' => $price / 100,
                'original_price' => null !== $channelPricing->getOriginalPrice() ? $channelPricing->getOriginalPrice() / 100 : null,
            ];
        }

        return $prices;
    }

    /**
     * @return array<int, string>
     */
    private function mapImages(object $imagesAware): array
    {
        if (!method_exists($imagesAware, 'getImages')) {
            return [];
        }

        $images = [];

        foreach ($imagesAware->getImages() as $image) {
            if (!method_exists($image, 'getPath')) {
                continue;
            }

            $path = $image->getPath();

            if (null !== $path && '' !== $path) {
                $images[] = $path;
            }
        }

        return $images;
    }

    private function formatDate(?\DateTimeInterface $date): ?string
    {
        return $date?->format('Y-m-d H:i:s');
    }
}
