<?php

declare(strict_types=1);

namespace Setono\SyliusFacebookPlugin\EventListener;

use Setono\TagBag\Tag\TagInterface;
use Setono\TagBag\Tag\TemplateTag;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class AddLibrarySubscriber extends TagSubscriber
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => [
                'add',
            ],
        ];
    }

    public function add(RequestEvent $event): void
    {
        $request = $event->getRequest();

        if (!$event->isMainRequest() || !$this->isShopContext($request)) {
            return;
        }

        // Only add the library on 'real' page loads, not AJAX requests like add to cart
        if ($request->isXmlHttpRequest()) {
            return;
        }

        if (!$this->hasPixels()) {
            return;
        }

        $this->tagBag->add(
            TemplateTag::create('@SetonoSyliusFacebookPlugin/Tag/library.html.twig', [
                'pixels' => $this->getPixels(),
            ])->withSection(TagInterface::SECTION_HEAD)
        );
    }
}
