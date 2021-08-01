<?php

namespace App\Entity\Book;

use InvalidArgumentException;

class Score
{
    public ?int $value = null;

    public function __construct(?int $value = null)
    {
        $this->assertValueIsValid($value);
        $this->value = $value;
    }

    public static function create(?int $value = null): self
    {
        return new self($value);
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    private function assertValueIsValid(?int $value)
    {
        if (null === $value) {
            return null;
        }
        if ($value < 0 || $value > 5) {
            throw new InvalidArgumentException('El score tiene que estar entre 0 y 5');
        }
    }
}
