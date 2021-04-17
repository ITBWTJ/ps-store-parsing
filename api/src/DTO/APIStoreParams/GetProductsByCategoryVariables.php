<?php

namespace PSStoreParsing\DTO\APIStoreParams;

use PSStoreParsing\Exceptions\ApiStore\InvalidArgumentException;

class GetProductsByCategoryVariables implements IVariables
{
    private const MAX_SIZE = 1000;
    public function __construct(
        private string $id,
        private int $size,
        private int $offset
    )
    {
        $this->checkSize($this->size);
        $this->checkOffset($this->offset);
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    private function checkSize(int $size): void
    {
        if ($size < 1 || $size > self::MAX_SIZE) {
            throw new InvalidArgumentException('Size cant be less then 1 or more than ' . self::MAX_SIZE);
        }
    }

    private function checkOffset(int $offset): void
    {
        if ($offset < 0 || $offset > self::MAX_SIZE) {
            throw new InvalidArgumentException('Offset cant be less then 0 or more than ' . self::MAX_SIZE);
        }
    }
}
