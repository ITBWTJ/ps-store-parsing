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
        private ?string $concept = null,
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

    /**
     * @return string|null
     */
    public function getConcept(): ?string
    {
        return $this->concept;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->getName(),
            'store_id' => $this->getStoreId(),
            'image_url' => $this->getImageUrl(),
            'type' => $this->getType(),
            'base_price' => $this->getBasePrice(),
            'discounted_price' => $this->getDiscountedPrice(),
            'is_exclusive' => (int)$this->isExclusive(),
            'end_time' => $this->getEndTime(),
            'platforms' => json_encode($this->getPlatforms(), JSON_THROW_ON_ERROR),
            'concept' => $this->getConcept(),
        ];
    }

    /**
     * @param string|null $imageUrl
     * @return Product
     */
    public function setImageUrl(?string $imageUrl): Product
    {
        $this->imageUrl = $imageUrl;
        return $this;
    }

    /**
     * @param array $platforms
     * @return Product
     */
    public function setPlatforms(array $platforms): Product
    {
        $this->platforms = $platforms;
        return $this;
    }

    /**
     * @param string $name
     * @return Product
     */
    public function setName(string $name): Product
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param string $storeId
     * @return Product
     */
    public function setStoreId(string $storeId): Product
    {
        $this->storeId = $storeId;
        return $this;
    }

    /**
     * @param string $type
     * @return Product
     */
    public function setType(string $type): Product
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @param int $basePrice
     * @return Product
     */
    public function setBasePrice(int $basePrice): Product
    {
        $this->basePrice = $basePrice;
        return $this;
    }

    /**
     * @param int|null $discountedPrice
     * @return Product
     */
    public function setDiscountedPrice(?int $discountedPrice): Product
    {
        $this->discountedPrice = $discountedPrice;
        return $this;
    }

    /**
     * @param int|null $endTime
     * @return Product
     */
    public function setEndTime(?int $endTime): Product
    {
        $this->endTime = $endTime;
        return $this;
    }

    /**
     * @param bool $isExclusive
     * @return Product
     */
    public function setIsExclusive(bool $isExclusive): Product
    {
        $this->isExclusive = $isExclusive;
        return $this;
    }

    /**
     * @param string|null $concept
     * @return Product
     */
    public function setConcept(?string $concept): Product
    {
        $this->concept = $concept;
        return $this;
    }
}
