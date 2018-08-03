<?php

namespace CustomerCategory\Loop;

use Thelia\Core\Template\Element\ArraySearchLoopInterface;
use Thelia\Core\Template\Element\BaseLoop;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Element\LoopResultRow;
use Thelia\Core\Template\Loop\Argument\Argument;
use Thelia\Core\Template\Loop\Argument\ArgumentCollection;
use Thelia\Model\Currency;
use Thelia\Model\ProductSaleElementsQuery;

/**
 * Class CustomerCategoryProductPriceLoop
 * @package CustomerCategory\Loop
 * @author Etienne Perriere <eperriere@openstudio.fr>
 */
class CustomerCategoryProductPriceLoop extends BaseLoop implements ArraySearchLoopInterface
{
    /**
     * Definition of loop arguments
     *
     * @return \Thelia\Core\Template\Loop\Argument\ArgumentCollection
     */
    protected function getArgDefinitions()
    {
        return new ArgumentCollection(
            Argument::createIntTypeArgument('pse_id', null, true),
            Argument::createIntTypeArgument('currency_id', Currency::getDefaultCurrency()->getId()),
            Argument::createIntTypeArgument('customer_category_id', null, true)
        );
    }

    /**
     * this method returns an array
     *
     * @return array
     */
    public function buildArray()
    {
        $items = [];

        $items['pse_id'] = $this->getPseId();
        $items['currency_id'] = $this->getCurrencyId();
        $items['customerCategoryId'] = $this->getCustomerCategoryId();

        return $items;
    }

    /**
     * @param LoopResult $loopResult
     *
     * @return LoopResult
     */
    public function parseResults(LoopResult $loopResult)
    {
        $items = $loopResult->getResultDataCollection();

        /** @var \CustomerCategory\Service\CustomerCategoryService $customerCategoryService */
        $customerCategoryService = $this->container->get('customer.category.service');

        $pse = ProductSaleElementsQuery::create()->findOneById($items['pse_id']);

        $prices = $customerCategoryService->calculateCustomerCategoryPsePrice($pse, $items['customerCategoryId'], $items['currency_id']);

        $loopResultRow = new LoopResultRow();

        $loopResultRow->set("CALCULATED_PRICE", $prices['price']);
        $loopResultRow->set("CALCULATED_TAXED_PRICE", $prices['taxedPrice']);
        $loopResultRow->set("CALCULATED_PROMO_PRICE", $prices['promoPrice']);
        $loopResultRow->set("CALCULATED_TAXED_PROMO_PRICE", $prices['taxedPromoPrice']);

        $loopResult->addRow($loopResultRow);

        return $loopResult;
    }
}
















