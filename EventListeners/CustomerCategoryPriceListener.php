<?php

namespace CustomerCategory\EventListeners;

use CustomerCategory\Model\Map\ProductPurchasePriceTableMap;
use CustomerCategory\Service\CustomerCategoryService;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\Join;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Thelia\Core\Event\Loop\LoopExtendsBuildModelCriteriaEvent;
use Thelia\Core\Event\Loop\LoopExtendsParseResultsEvent;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Core\Security\SecurityContext;
use Thelia\Exception\TaxEngineException;
use Thelia\Model\Currency;
use Thelia\Model\Map\ProductSaleElementsTableMap;
use Thelia\Model\Product;
use Thelia\Model\ProductQuery;
use Thelia\Model\ProductSaleElements;
use Thelia\TaxEngine\TaxEngine;

/**
 * Class CustomerCategoryPriceListener
 * @package CustomerCategory\EventListeners
 * @author Etienne Perriere <eperriere@openstudio.fr>
 */
class CustomerCategoryPriceListener implements EventSubscriberInterface
{
    protected $securityContext;
    protected $taxEngine;
    protected $customerCategoryService;

    public function __construct(SecurityContext $securityContext, TaxEngine $taxEngine, CustomerCategoryService $customerCategoryService)
    {
        $this->securityContext = $securityContext;
        $this->taxEngine = $taxEngine;
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
            TheliaEvents::getLoopExtendsEvent(TheliaEvents::LOOP_EXTENDS_BUILD_MODEL_CRITERIA, 'product') => ['extendProductModelCriteria', 128],
            TheliaEvents::getLoopExtendsEvent(TheliaEvents::LOOP_EXTENDS_PARSE_RESULTS, 'product') => ['extendProductParseResult', 128],
            TheliaEvents::getLoopExtendsEvent(TheliaEvents::LOOP_EXTENDS_BUILD_MODEL_CRITERIA, 'product_sale_elements') => ['extendProductModelCriteria', 128],
            TheliaEvents::getLoopExtendsEvent(TheliaEvents::LOOP_EXTENDS_PARSE_RESULTS, 'product_sale_elements') => ['extendProductParseResult', 128]
        ];
    }

    /**
     * @param LoopExtendsBuildModelCriteriaEvent $event
     * @return mixed
     */
    public function extendProductModelCriteria(LoopExtendsBuildModelCriteriaEvent $event)
    {
        // Get customer's category
        if (null !== $customerCategoryId = $this->customerCategoryService->getCustomerCustomerCategoryId()) {
            // Get associated prices
            $customerCategoryPrice = $this->customerCategoryService->getCustomerCategoryPrice($customerCategoryId, 0, 1);
            $customerCategoryPromoPrice = $this->customerCategoryService->getCustomerCategoryPrice($customerCategoryId, 1, 1);

            if ($customerCategoryPrice !== null || $customerCategoryPromoPrice !== null) {
                // Get currency & search
                $currencyId = Currency::getDefaultCurrency()->getId();
                $search = $event->getModelCriteria();

                // If $search is a ProductQuery, table alias is 'pse'
                // Else $search is a ProductSaleElementsQuery ans there is no table alias
                if ($search instanceof ProductQuery) {
                    $searchType = 'pse';
                } else {
                    $searchType = null;
                }

                // Link each PSE with its corresponding purchase price, according to the PSE id
                $productPurchasePriceJoin = new Join();
                $productPurchasePriceJoin->addExplicitCondition(
                    ProductSaleElementsTableMap::TABLE_NAME,
                    'ID',
                    $searchType,
                    ProductPurchasePriceTableMap::TABLE_NAME,
                    'PRODUCT_SALE_ELEMENTS_ID'
                );
                $productPurchasePriceJoin->setJoinType(Criteria::LEFT_JOIN);

                // Add the link to the search, and add a link condition based on the currency
                $search
                    ->addJoinObject($productPurchasePriceJoin, 'purchase_price_join')
                    ->addJoinCondition('purchase_price_join', ProductPurchasePriceTableMap::CURRENCY_ID.' = ?', $currencyId, null, \PDO::PARAM_INT);

                // Add
                $this->addProductCalculatedPrice($customerCategoryPrice, $search);
                $this->addProductCalculatedPromoPrice($customerCategoryPromoPrice, $search);
            }
        }
    }

    /**
     * @param LoopExtendsParseResultsEvent $event
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function extendProductParseResult(LoopExtendsParseResultsEvent $event)
    {
        // Get customer's category
        if (null !== $customerCategoryId = $this->customerCategoryService->getCustomerCustomerCategoryId()) {
            // Get associated prices
            $customerCategoryPrice = $this->customerCategoryService->getCustomerCategoryPrice($customerCategoryId, 0, 1);
            $customerCategoryPromoPrice = $this->customerCategoryService->getCustomerCategoryPrice($customerCategoryId, 1, 1);

            if ($customerCategoryPrice !== null || $customerCategoryPromoPrice !== null) {
                // Get loop result, tax country & security context
                $loopResult = $event->getLoopResult();
                $taxCountry = $this->taxEngine->getDeliveryCountry();
                $securityContext = $this->securityContext;

                foreach ($loopResult as $loopResultRow) {
                    /** @var \Thelia\Model\Product | \Thelia\Model\ProductSaleElements $product */
                    $product = $loopResultRow->model;

                    $customerCategoryPriceVirtualColumn = $product->getVirtualColumn('CUSTOMER_CATEGORY_PRICE');
                    $customerCategoryPromoPriceVirtualColumn = $product->getVirtualColumn('CUSTOMER_CATEGORY_PROMO_PRICE');

                    if (!empty($customerCategoryPriceVirtualColumn) || !empty($customerCategoryPromoPriceVirtualColumn)) {
                        $this->changeProductPrice(
                            $product,
                            $loopResultRow,
                            $taxCountry,
                            $securityContext
                        );
                    }
                }
            }
        }
    }

    /********************************/

    /**
     * @param \CustomerCategory\Model\CustomerCategoryPrice $customerCategoryPrice
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $search
     */
    protected function addProductCalculatedPrice($customerCategoryPrice, $search)
    {
        // Check if products' prices have to be changed depending on the customer's category
        if ($customerCategoryPrice !== null) {
            $search
                ->withColumn(
                    'IF (' . ProductPurchasePriceTableMap::PURCHASE_PRICE . ' IS NULL,
                        NULL,
                        (' .
                            ProductPurchasePriceTableMap::PURCHASE_PRICE .
                            '+' . $customerCategoryPrice->getAmountAddedBefore() .
                        ') * ' . $customerCategoryPrice->getMultiplicationCoefficient() .
                        ' + ' . $customerCategoryPrice->getAmountAddedAfter() .
                    ')',
                    'CUSTOMER_CATEGORY_PRICE'
                );
        } else {
            $search->withColumn('NULL', 'CUSTOMER_CATEGORY_PRICE');
        }
    }

    /**
     * @param \CustomerCategory\Model\CustomerCategoryPrice $customerCategoryPromoPrice
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $search
     */
    protected function addProductCalculatedPromoPrice($customerCategoryPromoPrice, $search)
    {
        // Check if products' promo prices have to be changed depending on the customer's category
        if ($customerCategoryPromoPrice !== null) {
            $search
                ->withColumn(
                    'IF (' . ProductPurchasePriceTableMap::PURCHASE_PRICE . ' IS NULL,
                        NULL,
                        (' .
                            ProductPurchasePriceTableMap::PURCHASE_PRICE .
                            '+' . $customerCategoryPromoPrice->getAmountAddedBefore() .
                        ') * ' . $customerCategoryPromoPrice->getMultiplicationCoefficient() .
                        ' + ' . $customerCategoryPromoPrice->getAmountAddedAfter() .
                    ')',
                    'CUSTOMER_CATEGORY_PROMO_PRICE'
                );
        } else {
            $search->withColumn('NULL', 'CUSTOMER_CATEGORY_PROMO_PRICE');
        }
    }

    /********************************/

    /**
     * @param \Thelia\Model\Product | \Thelia\Model\ProductSaleElements $product
     * @param \Thelia\Core\Template\Element\LoopResultRow               $loopResultRow
     * @param \Thelia\Model\Country                                     $taxCountry
     * @param SecurityContext                                           $securityContext
     */
    protected function changeProductPrice(
        $product,
        $loopResultRow,
        $taxCountry,
        SecurityContext $securityContext
    ) {
        $price = $loopResultRow->get('PRICE');
        $priceTax = $loopResultRow->get('PRICE_TAX');
        $taxedPrice = $loopResultRow->get('TAXED_PRICE');
        $promoPrice = $loopResultRow->get('PROMO_PRICE');
        $promoPriceTax = $loopResultRow->get('PROMO_PRICE_TAX');
        $taxedPromoPrice = $loopResultRow->get('TAXED_PROMO_PRICE');

        // Replace price
        $customerCategoryPriceVirtualColumn = $product->getVirtualColumn('CUSTOMER_CATEGORY_PRICE');
        if (!empty($customerCategoryPriceVirtualColumn)) {
            $price = round($product->getVirtualColumn('CUSTOMER_CATEGORY_PRICE'), 2);

            // If the customer has permanent discount, apply it
            if ($securityContext->hasCustomerUser() && $securityContext->getCustomerUser()->getDiscount() > 0) {
                $price = $price * (1 - ($securityContext->getCustomerUser()->getDiscount() / 100));
            }

            // Tax price
            try {
                // If $product is a Product, getTaxedPrice() takes a Country and a price as arguments
                // Else if $product is a ProductSaleElements, getTaxedPrice() takes a Country and the price virtual column name as arguments
                if ($product instanceof Product) {
                    $taxedPrice = $product->getTaxedPrice($taxCountry, $price);
                } elseif ($product instanceof ProductSaleElements) {
                    $taxedPrice = $product->getTaxedPrice($taxCountry, 'CUSTOMER_CATEGORY_PRICE');
                }
            } catch (TaxEngineException $e) {
                $taxedPrice = null;
            }

            $priceTax = $taxedPrice - $price;

            // Set new price & tax into the loop
            $loopResultRow
                ->set("PRICE", $price)
                ->set("PRICE_TAX", $priceTax)
                ->set("TAXED_PRICE", $taxedPrice);
        }

        // Replace promo price
        $customerCategoryPromoPriceVirtualColumn = $product->getVirtualColumn('CUSTOMER_CATEGORY_PROMO_PRICE');
        if (!empty($customerCategoryPromoPriceVirtualColumn)) {
            $promoPrice = round($product->getVirtualColumn('CUSTOMER_CATEGORY_PROMO_PRICE'), 2);

            // If the customer has permanent discount, apply it
            if ($securityContext->hasCustomerUser() && $securityContext->getCustomerUser()->getDiscount() > 0) {
                $promoPrice = $promoPrice * (1 - ($securityContext->getCustomerUser()->getDiscount() / 100));
            }

            // Tax promo price
            try {
                // If $product is a Product, getTaxedPrice() takes a Country and a price as arguments
                // Else if $product is a ProductSaleElements, getTaxedPrice() takes a Country and the price virtual column name as arguments
                if ($product instanceof Product) {
                    $taxedPromoPrice = $product->getTaxedPromoPrice($taxCountry, $promoPrice);
                } elseif ($product instanceof ProductSaleElements) {
                    $taxedPromoPrice = $product->getTaxedPromoPrice($taxCountry, 'CUSTOMER_CATEGORY_PROMO_PRICE');
                }
            } catch (TaxEngineException $e) {
                $taxedPromoPrice = null;
            }

            $promoPriceTax = $taxedPromoPrice - $promoPrice;

            // Set new promo price & tax into the loop
            $loopResultRow
                ->set("PROMO_PRICE", $promoPrice)
                ->set("PROMO_PRICE_TAX", $promoPriceTax)
                ->set("TAXED_PROMO_PRICE", $taxedPromoPrice);
        }

        // If current row is a product
        if ($product instanceof Product) {
            $loopResultRow
                ->set("BEST_PRICE", $product->getVirtualColumn('is_promo') ? $promoPrice : $price)
                ->set("BEST_PRICE_TAX", $product->getVirtualColumn('is_promo') ? $promoPriceTax : $priceTax)
                ->set("BEST_TAXED_PRICE", $product->getVirtualColumn('is_promo') ? $taxedPromoPrice : $taxedPrice);
        }
    }
}