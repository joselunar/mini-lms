<?php

namespace App\Services;

class CurrentUser
{
    /**
     * @var array<string, mixed>|null
     */
    private ?array $user = null;

    /**
     * @param array<string, mixed> $user
     */
    public function set(array $user): void
    {
        $this->user = $user;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function get(): ?array
    {
        return $this->user;
    }

    public function id(): int
    {
        return (int) ($this->user['id'] ?? 0);
    }

    public function isAdmin(): bool
    {
        return ($this->user['role'] ?? '') === 'admin';
    }

    public function isAuthenticated(): bool
    {
        return $this->user !== null;
    }
}
