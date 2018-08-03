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

use CustomerCategory\Model\CustomerCategory;
use Symfony\Component\Form\Form;
use Thelia\Core\Event\ActionEvent;

/**
 * Class CustomerCategoryEvent
 * @package CustomerCategory\Event
 */
class CustomerCategoryEvent extends ActionEvent
{
    /** @var CustomerCategory */
    private $customerCategory;

    public function __construct(CustomerCategory $customerCategory = null)
    {
        if ($customerCategory !== null) {
            $this->customerCategory = $customerCategory;
        } else {
            $this->customerCategory = new CustomerCategory();
        }
    }

    /**
     * @param CustomerCategory $customerCategory
     * @return $this
     */
    public function setCustomerCategory(CustomerCategory $customerCategory)
    {
        $this->customerCategory = $customerCategory;

        return $this;
    }

    /**
     * @return CustomerCategory
     */
    public function getCustomerCategory()
    {
        return $this->customerCategory;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->customerCategory->getId();
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->customerCategory->getCode();
    }

    /**
     * @param string $code
     * @return $this
     */
    public function setCode($code)
    {
        $this->customerCategory->setCode($code);

        return $this;
    }

    /**
     * @param $locale
     * @return string
     */
    public function getTitle($locale = null)
    {
        if ($locale === null) {
            $locale = $this->customerCategory->getLocale();
        }

        $this->customerCategory->setLocale($locale);

        return $this->customerCategory->getTitle();
    }

    /**
     * @param $title
     * @param null $locale
     * @return $this
     */
    public function setTitle($title, $locale = null)
    {
        if ($locale === null) {
            $locale = $this->customerCategory->getLocale();
        }

        $this->customerCategory->setLocale($locale);
        $this->customerCategory->setTitle($title);

        return $this;
    }

    /**
     * @param Form $form
     */
    public function hydrateByForm(Form $form)
    {
        //code
        if ($form->get('code') !== null) {
            self::setCode($form->get('code')->getData());
        }

        //title
        if ($form->get('title') !== null && $form->get('locale') !== null) {
            self::setTitle($form->get('title')->getData(), $form->get('locale')->getData());
        }
    }
}
