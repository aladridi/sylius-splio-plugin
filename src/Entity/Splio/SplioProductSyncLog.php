<?php

declare(strict_types=1);

namespace Dridialaa\SyliusSplioPlugin\Entity\Splio;

use Doctrine\ORM\Mapping as ORM;
use Dridialaa\SyliusSplioPlugin\Repository\Splio\SplioProductSyncLogRepository;

#[ORM\Entity(repositoryClass: SplioProductSyncLogRepository::class)]
#[ORM\Table(name: 'app_splio_product_sync_log')]
#[ORM\Index(name: 'IDX_SPLIO_PRODUCT_SYNC_LOG_STATUS', columns: ['status'])]
#[ORM\Index(name: 'IDX_SPLIO_PRODUCT_SYNC_LOG_PRODUCT_CODE', columns: ['product_code'])]
#[ORM\Index(name: 'IDX_SPLIO_PRODUCT_SYNC_LOG_CREATED_AT', columns: ['created_at'])]
class SplioProductSyncLog
{
    public const STATUS_SUCCESS = 'success';
    public const STATUS_FAILED = 'failed';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(name: 'product_code', length: 255)]
    private string $productCode;

    #[ORM\Column(length: 32)]
    private string $status;

    #[ORM\Column(length: 255)]
    private string $endpoint;

    /** @var array<string, mixed> */
    #[ORM\Column(type: 'json')]
    private array $payload;

    /** @var array<string, mixed>|null */
    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $response = null;

    #[ORM\Column(name: 'error_message', type: 'text', nullable: true)]
    private ?string $errorMessage = null;

    #[ORM\Column(name: 'created_at')]
    private \DateTimeImmutable $createdAt;

    /**
     * @param array<string, mixed> $payload
     * @param array<string, mixed>|null $response
     */
    private function __construct(
        string $productCode,
        string $status,
        string $endpoint,
        array $payload,
        ?array $response = null,
        ?string $errorMessage = null,
    ) {
        $this->productCode = $productCode;
        $this->status = $status;
        $this->endpoint = $endpoint;
        $this->payload = $payload;
        $this->response = $response;
        $this->errorMessage = $errorMessage;
        $this->createdAt = new \DateTimeImmutable();
    }

    /**
     * @param array<string, mixed> $payload
     * @param array<string, mixed> $response
     */
    public static function success(string $productCode, string $endpoint, array $payload, array $response): self
    {
        return new self($productCode, self::STATUS_SUCCESS, $endpoint, $payload, $response);
    }

    /**
     * @param array<string, mixed> $payload
     */
    public static function failed(string $productCode, string $endpoint, array $payload, string $errorMessage): self
    {
        return new self($productCode, self::STATUS_FAILED, $endpoint, $payload, null, $errorMessage);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductCode(): string
    {
        return $this->productCode;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    /**
     * @return array<string, mixed>
     */
    public function getPayload(): array
    {
        return $this->payload;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getResponse(): ?array
    {
        return $this->response;
    }

    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}
