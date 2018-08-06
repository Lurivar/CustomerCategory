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

namespace CustomerCategory\Event;

use CustomerCategory\Model\CustomerCustomerCategory;
use Thelia\Core\Event\ActionEvent;

/**
 * Class CustomerCustomerCategoryEvent
 * @package CustomerCategory\Event
 */
class CustomerCustomerCategoryEvent extends ActionEvent
{
    /** @var int */
    protected $customerId;

    /** @var array */
    protected $customerCategoryId;

    /**
     * @param int $customerId
     */
    public function __construct($customerId)
    {
        $this->customerId = $customerId;
    }

    /**
     * @param int $customerCategoryId
     *
     * @return CustomerCustomerCategory
     */
    public function setCustomerCategoryId($customerCategoryId)
    {
        $this->customerCategoryId = $customerCategoryId;

        return $this;
    }

    /**
     * @return int
     */
    public function getCustomerCategoryId()
    {
        return $this->customerCategoryId;
    }

    /**
     * @param int $customerId
     *
     * @return CustomerCustomerCategory
     */
    public function setCustomerId($customerId)
    {
        $this->customerId = $customerId;

        return $this;
    }

    /**
     * @return int
     */
    public function getCustomerId()
    {
        return $this->customerId;
    }

}
