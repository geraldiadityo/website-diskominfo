<?php

namespace App\Repositories\Contracts;

interface SiteSettingRepositoryInterface
{
    public function get(string $key, mixed $default = null): mixed;

    /**
     * @param  array<string, mixed>  $keys  Key => default value pairs
     * @return array<string, mixed>
     */
    public function getMultiple(array $keys): array;

    public function getSiteName(): string;

    public function getLogo(): ?string;

    public function getFavicon(): ?string;

    public function getDescription(): string;

    /**
     * @return array{email: string, phone: string, address: string}
     */
    public function getContact(): array;

    /**
     * @return array<int, array{day: string, time: string}>
     */
    public function getOperationalHours(): array;

    /**
     * @return array{facebook: ?string, instagram: ?string}
     */
    public function getSocial(): array;

    /**
     * @return array<string, string>
     */
    public function getJumbotron(): array;
}
