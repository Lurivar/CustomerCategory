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

use CustomerCategory\Model\CustomerCategoryQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Thelia\Core\Template\Element\BaseI18nLoop;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Element\LoopResultRow;
use Thelia\Core\Template\Element\PropelSearchLoopInterface;
use Thelia\Core\Template\Loop\Argument\Argument;
use Thelia\Core\Template\Loop\Argument\ArgumentCollection;
use Thelia\Type\AlphaNumStringListType;
use Thelia\Type\TypeCollection;

/**
 * Class CustomerCategoryLoop
 * @package CustomerCategory\Loop
 */
class CustomerCategoryLoop extends BaseI18nLoop implements PropelSearchLoopInterface
{
    /**
     * Definition of loop arguments
     *
     * @return \Thelia\Core\Template\Loop\Argument\ArgumentCollection
     */
    protected function getArgDefinitions()
    {
        return new ArgumentCollection(
            Argument::createIntListTypeArgument('id'),
            new Argument(
                'code',
                new TypeCollection(
                    new AlphaNumStringListType()
                )
            ),
            Argument::createIntListTypeArgument('exclude_id'),
            Argument::createBooleanTypeArgument('is_default')
        );
    }

    /**
     * this method returns a Propel ModelCriteria
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function buildModelCriteria()
    {
        $search = CustomerCategoryQuery::create();

        /* manage translations */
        $this->configureI18nProcessing($search, array('TITLE'));

        if (null !== $id = $this->getId()) {
            $search->filterById($id, Criteria::IN);
        }

        if (null !== $code = $this->getCode()) {
            $search->filterByCode($code, Criteria::IN);
        }

        if (null !== $excludeId = $this->getExcludeId()) {
            $search->filterById($excludeId, Criteria::NOT_IN);
        }

        if (null !== $isDefault = $this->getIsDefault()) {
            $search->filterByIsDefault($isDefault);
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
        foreach ($loopResult->getResultDataCollection() as $customerCategory) {
            /** @var \CustomerCategory\Model\CustomerCategory $customerCategory */
            $loopResultRow = new LoopResultRow($customerCategory);
            $loopResultRow
                ->set("CUSTOMER_CATEGORY_ID", $customerCategory->getId())
                ->set("CODE", $customerCategory->getCode())
                ->set("TITLE_CUSTOMER_CATEGORY", $customerCategory->getVirtualColumn('i18n_TITLE'))
                ->set("IS_DEFAULT", $customerCategory->getIsDefault());

            $loopResult->addRow($loopResultRow);
        }

        return $loopResult;
    }
}
