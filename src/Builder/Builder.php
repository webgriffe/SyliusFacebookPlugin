<?php

declare(strict_types=1);

namespace Setono\SyliusFacebookPlugin\Builder;

use Safe\Exceptions\JsonException;
use Webmozart\Assert\Assert;
use function Safe\json_decode;
use function Safe\json_encode;

abstract class Builder implements BuilderInterface
{
    protected array $data = [];

    public static function create(): static
    {
        return new static();
    }

    /**
     * @throws JsonException
     */
    public static function createFromJson(string $json): static
    {
        $new = new static();
        $data = json_decode($json, true);
        Assert::isArray($data);
        $new->data = $data;

        return $new;
    }

    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @throws JsonException
     */
    public function getJson(): string
    {
        return json_encode($this->data);
    }
}
