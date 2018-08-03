<?php

namespace CustomerCategory\EventListeners;

use CustomerCategory\Service\CustomerCategoryService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Thelia\Core\Event\Cart\CartEvent;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Model\ProductSaleElementsQuery;

/**
 * Class CustomerCategoryCartListener
 * @package CustomerCategory\EventListeners
 * @author Etienne Perriere <eperriere@openstudio.fr>
 */
class CustomerCategoryCartListener implements EventSubscriberInterface
{
    protected $customerCategoryService;

    public function __construct(CustomerCategoryService $customerCategoryService)
    {
        $this->customerCategoryService = $customerCategoryService;
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            TheliaEvents::CART_ADDITEM => ['addCartItem', 128]
        ];
    }

    /**
     * @param CartEvent $cartEvent
     * @throws \Exception
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function addCartItem(CartEvent $cartEvent)
    {
        $pseId = $cartEvent->getProductSaleElementsId();
        $pse = ProductSaleElementsQuery::create()->findOneById($pseId);

        $prices = $this->customerCategoryService->calculateCustomerPsePrice($pse);

        if ($prices['price'] !== null) {
            $cartEvent->getCartItem()->setPrice($prices['price']);
        }
        if ($prices['promoPrice'] !== null) {
            $cartEvent->getCartItem()->setPromoPrice($prices['promoPrice']);
        }

        $cartEvent->getCartItem()->save();
    }
}