<?php

namespace CustomerCategory\EventListeners;

use CustomerCategory\Service\CustomerCategoryService;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Thelia\Core\Event\Customer\CustomerLoginEvent;
use Thelia\Core\Event\DefaultActionEvent;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Core\HttpFoundation\Request;
use Thelia\Model\ProductSaleElementsQuery;

/**
 * Class CustomerCategoryCustomerConnectionListener
 * @package CustomerCategory\EventListeners
 * @author Etienne Perriere <eperriere@openstudio.fr>
 */
class CustomerCategoryCustomerConnectionListener implements EventSubscriberInterface
{
    protected $request;
    protected $customerCategoryService;

    public function __construct(Request $request, CustomerCategoryService $customerCategoryService)
    {
        $this->request = $request;
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
            TheliaEvents::CUSTOMER_LOGOUT => ['customerLogout', 128],
            TheliaEvents::CUSTOMER_LOGIN => ['customerLogin', 128]
        ];
    }

    /**
     * Update cart items' prices when logging out
     *
     * @param DefaultActionEvent $event
     * @param $eventName
     * @param EventDispatcherInterface $dispatcher
     * @throws \Exception
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function customerLogout(DefaultActionEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        // Get cart & loop on its items
        $cart = $this->request->getSession()->getSessionCart($dispatcher);

        /** @var \Thelia\Model\CartItem $cartItem */
        foreach ($cart->getCartItems() as $cartItem) {
            // Get item's corresponding PSE
            $pse = ProductSaleElementsQuery::create()->findOneById($cartItem->getProductSaleElementsId());

            // Get pse's prices for the customer
            $prices = $this->customerCategoryService->calculateCustomerPsePrice(
                $pse,
                null,
                $cart->getCurrencyId()
            );

            if ($prices['price'] !== null) {
                $cartItem->setPrice($prices['price']);
            }

            if ($prices['promoPrice'] !== null) {
                $cartItem->setPromoPrice($prices['promoPrice']);
            }

            $cartItem->save();
        }
    }

    /**
     * Update cart items' prices when logging in
     *
     * @param CustomerLoginEvent $event
     * @param $eventName
     * @param EventDispatcherInterface $dispatcher
     * @throws \Exception
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function customerLogin(CustomerLoginEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        // Get cart & loop on its items
        $cart = $this->request->getSession()->getSessionCart($dispatcher);

        /** @var \Thelia\Model\CartItem $cartItem */
        foreach ($cart->getCartItems() as $cartItem) {
            // Get item's corresponding PSE
            $pse = ProductSaleElementsQuery::create()->findOneById($cartItem->getProductSaleElementsId());

            // Get pse's prices for the customer
            $prices = $this->customerCategoryService->calculateCustomerPsePrice(
                $pse,
                $event->getCustomer()->getId(),
                $cart->getCurrencyId()
            );

            if ($prices['price'] !== null) {
                $cartItem->setPrice($prices['price']);
            }

            if ($prices['promoPrice'] !== null) {
                $cartItem->setPromoPrice($prices['promoPrice']);
            }

            $cartItem->save();
        }
    }
}