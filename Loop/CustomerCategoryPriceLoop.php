<?php

namespace CustomerCategory\Loop;

use CustomerCategory\Model\CustomerCategoryPriceQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Thelia\Core\Template\Element\BaseLoop;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Element\LoopResultRow;
use Thelia\Core\Template\Element\PropelSearchLoopInterface;
use Thelia\Core\Template\Loop\Argument\Argument;
use Thelia\Core\Template\Loop\Argument\ArgumentCollection;

/**
 * Class CustomerCategoryPriceLoop
 * @package CustomerCategory\Loop
 * @author Etienne Perriere <eperriere@openstudio.fr>
 */
class CustomerCategoryPriceLoop extends BaseLoop implements PropelSearchLoopInterface
{
    /**
     * Definition of loop arguments
     *
     * @return \Thelia\Core\Template\Loop\Argument\ArgumentCollection
     */
    protected function getArgDefinitions()
    {
        return new ArgumentCollection(
            Argument::createIntListTypeArgument('customer_category_id'),
            Argument::createBooleanTypeArgument('promo'),
            Argument::createBooleanTypeArgument('use_equation')
        );
    }

    /**
     * @return CustomerCategoryPriceQuery
     */
    public function buildModelCriteria()
    {
        $search = CustomerCategoryPriceQuery::create();

        if (null !== $customerCategoryId = $this->getCustomerCategoryId()) {
            $search->filterByCustomerCategoryId($customerCategoryId, Criteria::IN);
        }

        if (null !== $promo = $this->getPromo()) {
            $search->filterByPromo($promo);
        }

        if (null !== $useEquation = $this->getUseEquation()) {
            $search->filterByUseEquation($useEquation);
        }

        return $search;
    }

    /**
     * @param LoopResult $loopResult
     *
     * @return LoopResult
     */
    public function parseResults(LoopResult $loopResult)
    {
        /** @var \CustomerCategory\Model\CustomerCategoryPrice $customerCategoryPrice */
        foreach ($loopResult->getResultDataCollection() as $customerCategoryPrice) {
            $loopResultRow = new LoopResultRow($customerCategoryPrice);

            $loopResultRow
                ->set('CUSTOMER_CATEGORY_ID', $customerCategoryPrice->getCustomerCategoryId())
                ->set('PROMO', $customerCategoryPrice->getPromo())
                ->set('USE_EQUATION', $customerCategoryPrice->getUseEquation())
                ->set('AMOUNT_ADDED_BEFORE', $customerCategoryPrice->getAmountAddedBefore())
                ->set('AMOUNT_ADDED_AFTER', $customerCategoryPrice->getAmountAddedAfter())
                ->set('COEFFICIENT', $customerCategoryPrice->getMultiplicationCoefficient())
                ->set('IS_TAXED', $customerCategoryPrice->getIsTaxed());

            $loopResult->addRow($loopResultRow);
        }

        return $loopResult;
    }
}