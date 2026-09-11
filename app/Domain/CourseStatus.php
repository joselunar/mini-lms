<?php

namespace App\Domain;

class CourseStatus
{
    public const DRAFT     = 'rascunho';
    public const PUBLISHED = 'publicado';
    public const CLOSED    = 'encerrado';

    public function __construct(private readonly string $value)
    {
        if (! in_array($value, self::all(), true)) {
            throw new DomainException('Status de curso inválido.', 422);
        }
    }

    /**
     * @return list<string>
     */
    public static function all(): array
    {
        return [self::DRAFT, self::PUBLISHED, self::CLOSED];
    }

    public function value(): string
    {
        return $this->value;
    }

    public function isVisibleInCatalog(): bool
    {
        return $this->value === self::PUBLISHED;
    }

    public function canEnroll(): bool
    {
        return $this->value === self::PUBLISHED;
    }

    public function canReceiveProgress(): bool
    {
        return in_array($this->value, [self::PUBLISHED, self::CLOSED], true);
    }

    public function canPublish(): bool
    {
        return $this->value === self::DRAFT;
    }

    public function canClose(): bool
    {
        return $this->value === self::PUBLISHED;
    }
}
