<?php

declare(strict_types=1);

namespace Setono\SyliusFacebookPlugin\Renderer;

use Setono\SyliusFacebookPlugin\Tag\FbqTagInterface;
use Setono\TagBag\Renderer\RendererInterface;
use Setono\TagBag\Tag\TagInterface;
use Twig\Environment;
use Webmozart\Assert\Assert;

final class FbqRenderer implements RendererInterface
{
    public function __construct(
        private Environment $environment,
    ) {
    }

    public function supports(TagInterface $tag): bool
    {
        return $tag instanceof FbqTagInterface && $tag->getTemplateType() === 'twig';
    }

    public function render(TagInterface $tag): string
    {
        Assert::true($this->supports($tag));
        Assert::isInstanceOf($tag, FbqTagInterface::class);

        return $this->environment->render($tag->getTemplate(), $tag->getParameters());
    }
}
