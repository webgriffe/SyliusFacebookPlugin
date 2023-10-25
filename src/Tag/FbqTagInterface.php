<?php

declare(strict_types=1);

namespace Setono\SyliusFacebookPlugin\Tag;

use Setono\TagBag\Tag\TagInterface;

interface FbqTagInterface extends TagInterface
{
    public const EVENT_ADD_TO_CART = 'AddToCart';

    public const EVENT_ADD_PAYMENT_INFO = 'AddPaymentInfo';

    public const EVENT_ADD_TO_WISHLIST = 'AddToWishlist';

    public const EVENT_COMPLETE_REGISTRATION = 'CompleteRegistration';

    public const EVENT_CONTACT = 'Contact';

    public const EVENT_CUSTOMIZE_PRODUCT = 'CustomizeProduct';

    public const EVENT_DONATE = 'Donate';

    public const EVENT_FIND_LOCATION = 'FindLocation';

    public const EVENT_INITIATE_CHECKOUT = 'InitiateCheckout';

    public const EVENT_LEAD = 'Lead';

    public const EVENT_PURCHASE = 'Purchase';

    public const EVENT_SCHEDULE = 'Schedule';

    public const EVENT_SEARCH = 'Search';

    public const EVENT_START_TRIAL = 'StartTrial';

    public const EVENT_SUBMIT_APPLICATION = 'SubmitApplication';

    public const EVENT_SUBSCRIBE = 'Subscribe';

    public const EVENT_VIEW_CONTENT = 'ViewContent';

    /**
     * Returns the template type. For example a twig template would return 'twig'
     * This is helpful when creating renderers that should support generic tags. At runtime these tags
     * can return the correct template type and subsequently be rendered by the correct renderer
     */
    public function getTemplateType(): string;

    public function getTemplate(): string;

    /**
     * Returns the data to inject into the template
     */
    public function getParameters(): array;
}
