<?php

namespace CustomerCategory\Service;

use CustomerCategory\Model\CustomerCustomerCategoryQuery;
use CustomerCategory\Model\CustomerCategoryPriceQuery;
use CustomerCategory\Model\CustomerCategoryQuery;
use CustomerCategory\Model\Map\CustomerCustomerCategoryTableMap;
use CustomerCategory\Model\Map\CustomerCategoryTableMap;
use CustomerCategory\Model\ProductPurchasePriceQuery;
use Thelia\Core\Security\SecurityContext;
use Thelia\Exception\TaxEngineException;
use Thelia\Model\Currency;
use Thelia\TaxEngine\TaxEngine;

/**
 * Class CustomerCategoryService
 * @package CustomerCategory\Service
 * @author Etienne Perriere <eperriere@openstudio.fr>
 */
class CustomerCategoryService
{
    protected $securityContext;
    protected $taxEngine;

    public function __construct(SecurityContext $securityContext, TaxEngine $taxEngine)
    {
        $this->securityContext = $securityContext;
        $this->taxEngine = $taxEngine;
    }

    /**
     * @param null $customerId
     * @return mixed
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function getCustomerCustomerCategoryId($customerId = null)
    {
        $customerCategoryId = null;

        // Get given customer's category, else logged customer's one
        if ($customerId !== null) {
            $customerCategoryId = CustomerCustomerCategoryQuery::create()
                ->filterByCustomerId($customerId)
                ->select(CustomerCustomerCategoryTableMap::CUSTOMER_CATEGORY_ID)
                ->findOne();
        } elseif ($this->securityContext->hasCustomerUser()) {
            $customerCategoryId = CustomerCustomerCategoryQuery::create()
                ->filterByCustomerId($this->securityContext->getCustomerUser()->getId())
                ->select(CustomerCustomerCategoryTableMap::CUSTOMER_CATEGORY_ID)
                ->findOne();
        }

        // If no category found, get default one
        if ($customerCategoryId === null) {
            $customerCategoryId = CustomerCategoryQuery::create()
                ->filterByIsDefault(1)
                ->select(CustomerCategoryTableMap::ID)
                ->findOne();
        }

        return $customerCategoryId;
    }

    /**
     * @param $pseId
     * @param $currencyId
     * @return \CustomerCategory\Model\ProductPurchasePrice
     */
    public function getPseProductPurchasePrice($pseId, $currencyId)
    {
        return ProductPurchasePriceQuery::create()
            ->filterByCurrencyId($currencyId)
            ->findOneByProductSaleElementsId($pseId);
    }

    /**
     * @param $customerCategoryId
     * @param int $isPromo
     * @param null $useEquation
     * @return \CustomerCategory\Model\CustomerCategoryPrice
     */
    public function getCustomerCategoryPrice($customerCategoryId, $isPromo = 0, $useEquation = null)
    {
        $search = CustomerCategoryPriceQuery::create()
            ->filterByPromo($isPromo)
            ->filterByCustomerCategoryId($customerCategoryId);

        if ($useEquation !== null) {
            $search->filterByUseEquation($useEquation);
        }

        return $search->findOne();
    }

    /**
     * @param \Thelia\Model\ProductSaleElements $pse
     * @param $customerCategoryId
     * @param null $currencyId
     * @return array
     */
    public function calculateCustomerCategoryPsePrice($pse, $customerCategoryId, $currencyId = null)
    {
        $taxCountry = $this->taxEngine->getDeliveryCountry();

        // Get default currency if no one is given
        if ($currencyId === null) {
            $currencyId = Currency::getDefaultCurrency()->getId();
        }

        // If the purchase price & its price exist
        if (null !== $productPurchasePrice = $this->getPseProductPurchasePrice($pse->getId(), $currencyId)) {
            if (null !== $productPurchasePricePrice = $productPurchasePrice->getPurchasePrice()) {
                // Initialize prices
                $price = $taxedPrice = $promoPrice = $taxedPromoPrice = null;

                // Standard price
                if (null !== $customerCategoryPrice = $this->getCustomerCategoryPrice($customerCategoryId, 0, 1)) {
                    // Calculate price
                    $price = round(
                        ($productPurchasePrice->getPurchasePrice() + $customerCategoryPrice->getAmountAddedBefore())
                        * $customerCategoryPrice->getMultiplicationCoefficient()
                        + $customerCategoryPrice->getAmountAddedAfter(),
                        2
                    );

                    $pse->setVirtualColumn('CUSTOMER_CATEGORY_PRICE', $price);

                    // Tax
                    try {
                        $taxedPrice = $pse->getTaxedPrice($taxCountry, 'CUSTOMER_CATEGORY_PRICE');
                    } catch (TaxEngineException $e) {
                        $taxedPrice = null;
                    }
                }

                // Promo price
                if (null !== $customerCategoryPromoPrice = $this->getCustomerCategoryPrice($customerCategoryId, 1, 1)) {
                    // Calculate promo price
                    $promoPrice = round(
                        ($productPurchasePrice->getPurchasePrice() + $customerCategoryPromoPrice->getAmountAddedBefore())
                        * $customerCategoryPromoPrice->getMultiplicationCoefficient()
                        + $customerCategoryPromoPrice->getAmountAddedAfter(),
                        2
                    );

                    $pse->setVirtualColumn('CUSTOMER_CATEGORY_PROMO_PRICE', $promoPrice);

                    // Tax
                    try {
                        $taxedPromoPrice = $pse->getTaxedPrice($taxCountry, 'CUSTOMER_CATEGORY_PROMO_PRICE');
                    } catch (TaxEngineException $e) {
                        $taxedPromoPrice = null;
                    }
                }

                return [
                    'price' => $price,
                    'taxedPrice' => $taxedPrice,
                    'promoPrice' => $promoPrice,
                    'taxedPromoPrice' => $taxedPromoPrice
                ];
            }
        }

        return null;
    }

    /**
     * @param \Thelia\Model\ProductSaleElements $pse
     * @param null $customerId
     * @param null $currencyId
     * @return array
     */
    public function calculateCustomerPsePrice($pse, $customerId = null, $currencyId = null)
    {
        // Get customer's category
        $customerCategoryId = $this->getCustomerCustomerCategoryId($customerId);

        return $this->calculateCustomerCategoryPsePrice($pse, $customerCategoryId, $currencyId);
    }
}