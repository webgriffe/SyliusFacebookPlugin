<?php

declare(strict_types=1);

namespace Setono\SyliusFacebookPlugin\EventListener;

use Setono\SyliusFacebookPlugin\Builder\AddToCartBuilder;
use Setono\SyliusFacebookPlugin\Builder\ContentBuilder;
use Setono\SyliusFacebookPlugin\Context\PixelContextInterface;
use Setono\SyliusFacebookPlugin\Event\BuilderEvent;
use Setono\SyliusFacebookPlugin\Tag\FbqTag;
use Setono\SyliusFacebookPlugin\Tag\FbqTagInterface;
use Setono\TagBag\Tag\TagInterface;
use Setono\TagBag\TagBagInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Symfony\Bundle\SecurityBundle\Security\FirewallMap;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Webmozart\Assert\Assert;

final class AddToCartSubscriber extends TagSubscriber
{
    private CartContextInterface $cartContext;

    public function __construct(
        TagBagInterface $tagBag,
        PixelContextInterface $pixelContext,
        EventDispatcherInterface $eventDispatcher,
        CartContextInterface $cartContext,
        RequestStack $requestStack,
        FirewallMap $firewallMap
    ) {
        parent::__construct($tagBag, $pixelContext, $eventDispatcher, $requestStack, $firewallMap);

        $this->cartContext = $cartContext;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'sylius.order_item.post_add' => [
                'track',
            ],
        ];
    }

    public function track(): void
    {
        if (!$this->isShopContext() || !$this->hasPixels()) {
            return;
        }

        $order = $this->cartContext->getCart();

        if (!$order instanceof OrderInterface) {
            return;
        }

        $value = $this->moneyFormatter->format($order->getTotal());
        Assert::float($value);
        $builder = AddToCartBuilder::create()
            ->setCurrency((string) $order->getCurrencyCode())
            ->setValue($value)
            ->setContentType(AddToCartBuilder::CONTENT_TYPE_PRODUCT)
        ;

        foreach ($order->getItems() as $item) {
            $variant = $item->getVariant();
            if (null === $variant) {
                continue;
            }

            $builder->addContentId($variant->getCode());

            $itemPrice = $this->moneyFormatter->format($item->getDiscountedUnitPrice());
            Assert::float($itemPrice);
            $contentBuilder = ContentBuilder::create()
                ->setId($variant->getCode())
                ->setQuantity($item->getQuantity())
                ->setItemPrice($itemPrice)
            ;

            $this->eventDispatcher->dispatch(new BuilderEvent($contentBuilder, $item));

            $builder->addContent($contentBuilder);
        }

        $this->eventDispatcher->dispatch(new BuilderEvent($builder, $order));

        $this->tagBag->add(
            (new FbqTag(FbqTagInterface::EVENT_ADD_TO_CART, $builder))
                ->withSection(TagInterface::SECTION_BODY_END)
        );
    }
}
