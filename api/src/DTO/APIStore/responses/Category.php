<?php

namespace PSStoreParsing\DTO\APIStore\Responses;

class Category
{
    public function __construct(
        private string $id,
        private string $imageUrl,
        private string $linkTarget,
        private string $name,
    )
    {
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
    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

}
