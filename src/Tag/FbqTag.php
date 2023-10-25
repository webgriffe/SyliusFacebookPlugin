<?php

declare(strict_types=1);

namespace Setono\SyliusFacebookPlugin\Tag;

use Setono\SyliusFacebookPlugin\Builder\BuilderInterface;
use Setono\TagBag\Tag\Tag;

class FbqTag extends Tag implements FbqTagInterface
{
    private string $template = '@SetonoSyliusFacebookPlugin/Tag/event.html.twig';

    public function __construct(
        private string $event,
        private ?BuilderInterface $parameters = null,
        private string $method = 'track',
    ) {
    }

    public function getContext(): array
    {
        return $this->getParameters();
    }

    public function getParameters(): array
    {
        $ret = ['method' => $this->method, 'event' => $this->event];

        if (null !== $this->parameters) {
            $ret['parameters'] = $this->parameters->getJson();
        }

        return $ret;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function getTemplateType(): string
    {
        return strtolower(pathinfo($this->template, PATHINFO_EXTENSION));
    }
}
