<?php


namespace PSStoreParsing\Models;


class Product
{
    public function __construct(
        private string $id,
        private string $name,
        private string $storeId,
        private string $type,
        private int $basePrice,
        private ?int $discountedPrice,
        private ?int $endTime,
        private ?string $imageUrl,
        private bool $isExclusive = false,
        private array $platforms = [],
    ) {}

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getStoreId(): string
    {
        return $this->storeId;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getBasePrice(): int
    {
        return $this->basePrice;
    }

    /**
     * @return int|null
     */
    public function getDiscountedPrice(): ?int
    {
        return $this->discountedPrice;
    }

    /**
     * @return int|null
     */
    public function getEndTime(): ?int
    {
        return $this->endTime;
    }

    /**
     * @return string|null
     */
    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    /**
     * @return bool
     */
    public function isExclusive(): bool
    {
        return $this->isExclusive;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getPlatforms(): array
    {
        return $this->platforms;
    }
}
