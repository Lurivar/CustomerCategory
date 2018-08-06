<?php
/*************************************************************************************/
/*      This file is part of the module CustomerCategory                               */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : dev@thelia.net                                                       */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

namespace CustomerCategory\Loop;

use CustomerCategory\Model\CustomerCustomerCategoryQuery;
use CustomerCategory\Model\Map\CustomerCustomerCategoryTableMap;
use CustomerCategory\Model\Map\CustomerCategoryTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\Join;
use Thelia\Core\Template\Element\BaseLoop;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Element\LoopResultRow;
use Thelia\Core\Template\Element\PropelSearchLoopInterface;
use Thelia\Core\Template\Loop\Argument\Argument;
use Thelia\Core\Template\Loop\Argument\ArgumentCollection;

/**
 * Class CustomerCustomerCategoryLoop
 * @package CustomerCategory\Loop
 */
class CustomerCustomerCategoryLoop extends BaseLoop implements PropelSearchLoopInterface
{
    /**
     * Definition of loop arguments
     *
     * @return \Thelia\Core\Template\Loop\Argument\ArgumentCollection
     */
    protected function getArgDefinitions()
    {
        return new ArgumentCollection(
            Argument::createIntListTypeArgument('customer_id'),
            Argument::createIntListTypeArgument('customer_category_id'),
            Argument::createAnyTypeArgument('customer_category_code')
        );
    }

    /**
     * this method returns a Propel ModelCriteria
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function buildModelCriteria()
    {
        $search = CustomerCustomerCategoryQuery::create();

        if (null !== $customerId = $this->getCustomerId()) {
            $search->filterByCustomerId($customerId, Criteria::IN);
        }

        if (null !== $customerCategoryId = $this->getCustomerCategoryId()) {
            $search->filterByCustomerCategoryId($customerCategoryId, Criteria::IN);
        }

        if (null !== $customerCategoryCode = $this->getCustomerCategoryCode()) {
            $join =  new Join(
                CustomerCustomerCategoryTableMap::CUSTOMER_CATEGORY_ID,
                CustomerCategoryTableMap::ID,
                Criteria::INNER_JOIN
            );

            $search->addJoinObject($join, "customer_category_join")
                ->addJoinCondition("customer_category_join", CustomerCategoryTableMap::CODE." = '$customerCategoryCode'");
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
        foreach ($loopResult->getResultDataCollection() as $customerCustomerCategory) {
            /** @var \CustomerCategory\Model\CustomerCustomerCategory $customerCustomerCategory */
            $loopResultRow = new LoopResultRow($customerCustomerCategory);
            $loopResultRow
                ->set("CUSTOMER_CATEGORY_ID", $customerCustomerCategory->getCustomerCategoryId())
                ->set("CUSTOMER_ID", $customerCustomerCategory->getCustomerId())
            ;

            $loopResult->addRow($loopResultRow);
        }

        return $loopResult;
    }
}
