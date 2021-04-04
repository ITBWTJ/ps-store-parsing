<?php


namespace PSStoreParsing\Models;


class Category
{
    public function __construct(
        private int $id,
        private string $name,
        private string $storeId,
        private string $imageUrl,
        private string $linkTarget
    ) {}

    /**
     * @return string
     */
    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getLinkTarget(): string
    {
        return $this->linkTarget;
    }

    /**
     * @return string
     */
    public function getStoreId(): string
    {
        return $this->storeId;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}
