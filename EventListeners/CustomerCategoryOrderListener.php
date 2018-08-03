<?php

namespace CustomerCategory\EventListeners;

use CustomerCategory\Model\CustomerCategoryOrder;
use CustomerCategory\Model\CustomerCategoryPriceQuery;
use CustomerCategory\Model\CustomerCategoryQuery;
use CustomerCategory\Model\Map\ProductPurchasePriceTableMap;
use CustomerCategory\Model\OrderProductPurchasePrice;
use CustomerCategory\Model\ProductPurchasePriceQuery;
use CustomerCategory\Service\CustomerCategoryService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Thelia\Core\Event\Order\OrderEvent;
use Thelia\Core\Event\TheliaEvents;

/**
 * Class CustomerCategoryOrderListener
 * @package CustomerCategory\EventListeners
 * @author Etienne Perriere <eperriere@openstudio.fr>
 */
class CustomerCategoryOrderListener implements EventSubscriberInterface
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
            TheliaEvents::ORDER_BEFORE_PAYMENT => ['createOrderPurchasePrices', 128],
            TheliaEvents::ORDER_AFTER_CREATE => ['saveOrderCategoryAndEquation', 128]
        ];
    }

    /**
     * Save purchase price for each product of the order
     *
     * @param OrderEvent $orderEvent
     * @throws \Exception
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function createOrderPurchasePrices(OrderEvent $orderEvent)
    {
        $orderProducts = $orderEvent->getOrder()->getOrderProducts();
        $currencyId = $orderEvent->getOrder()->getCurrencyId();
        $customerCategoryId = $this->customerCategoryService->getCustomerCustomerCategoryId($orderEvent->getOrder()->getCustomerId());

        /** @var \Thelia\Model\OrderProduct $orderProduct */
        foreach ($orderProducts as $orderProduct) {
            // If a ProductPurchasePrice exists for the PSE & currency
            if (null !== $purchasePrice = ProductPurchasePriceQuery::create()
                    ->filterByProductSaleElementsId($orderProduct->getProductSaleElementsId())
                    ->filterByCurrencyId($currencyId)
                    ->select(ProductPurchasePriceTableMap::PURCHASE_PRICE)
                    ->findOne()) {
                // Initialize equation
                $equation = 'Equation not used';

                // If equation was used, get information about it
                if (null !== $customerCategoryPrice = $this->customerCategoryService->getCustomerCategoryPrice(
                    $customerCategoryId, $orderProduct->getWasInPromo(), 1)) {

                    $equation = '( ' . $purchasePrice . ' + ' . $customerCategoryPrice->getAmountAddedBefore() .
                        ' ) x ' . $customerCategoryPrice->getMultiplicationCoefficient() . ' + ' .
                        $customerCategoryPrice->getAmountAddedAfter();
                }

                // New OrderProductPurchasePrice
                (new OrderProductPurchasePrice())
                    ->setOrderProductId($orderProduct->getId())
                    ->setPurchasePrice($purchasePrice)
                    ->setSaleDayEquation($equation)
                    ->save();
            }
        }
    }

    public function saveOrderCategoryAndEquation(OrderEvent $orderEvent)
    {
        $customerCategory = CustomerCategoryQuery::create()
            ->findOneById(
                $this->customerCategoryService->getCustomerCustomerCategoryId($orderEvent->getOrder()->getCustomerId())
            );

        (new CustomerCategoryOrder())
            ->setOrderId($orderEvent->getOrder()->getId())
            ->setCustomerCategoryId($customerCategory->getId())
            ->save();
    }
}