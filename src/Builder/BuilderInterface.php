<?php

declare(strict_types=1);

namespace Setono\SyliusFacebookPlugin\Builder;

interface BuilderInterface
{
    public const CONTENT_TYPE_PRODUCT = 'product';

    public const CONTENT_TYPE_PRODUCT_GROUP = 'product_group';

    public static function create(): static;

    public static function createFromJson(string $json): static;

    public function getData(): array;

    public function getJson(): string;
}
