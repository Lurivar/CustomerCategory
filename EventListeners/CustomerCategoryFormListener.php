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

namespace CustomerCategory\EventListeners;

use CustomerCategory\CustomerCategory;
use CustomerCategory\Model\CustomerCustomerCategoryQuery;
use CustomerCategory\Model\CustomerCategoryQuery;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Thelia\Action\BaseAction;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Core\Event\TheliaFormEvent;
use Thelia\Core\HttpFoundation\Request;
use Thelia\Core\Translation\Translator;

class CustomerCategoryFormListener extends BaseAction implements EventSubscriberInterface
{
    /** 'thelia_customer_create' is the name of the form used to create Customers (Thelia\Form\CustomerCreateForm). */
    const THELIA_CUSTOMER_CREATE_FORM_NAME = 'thelia_customer_create';
    const THELIA_ADMIN_CUSTOMER_CREATE_FORM_NAME = 'thelia_customer_create';

    /**
     * 'thelia_customer_profile_update' is the name of the form used to update accounts
     * (Thelia\Form\CustomerProfileUpdateForm).
     */
    const THELIA_ACCOUNT_UPDATE_FORM_NAME = 'thelia_customer_profile_update';

    const CUSTOMER_CATEGORY_CODE_FIELD_NAME = 'customer_category_code';

    /** @var \Thelia\Core\HttpFoundation\Request */
    protected $request;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * @return array The event names to listen to
     *
     * @api
     */
    public static function getSubscribedEvents()
    {
        return array(
            TheliaEvents::FORM_AFTER_BUILD.'.'.self::THELIA_CUSTOMER_CREATE_FORM_NAME => array('addCustomerCategoryFieldsForRegister', 128),
            TheliaEvents::FORM_AFTER_BUILD.'.'.self::THELIA_ADMIN_CUSTOMER_CREATE_FORM_NAME => array('addCustomerCategoryFieldsForRegister', 128),
            TheliaEvents::FORM_AFTER_BUILD.'.'.self::THELIA_ACCOUNT_UPDATE_FORM_NAME  => array('addCustomerCategoryFieldsForUpdate', 128),
        );
    }

    /**
     * Callback used to add some fields to the Thelia's CustomerCreateForm.
     * It add two fields : one for the SIRET number and one for VAT.
     * @param TheliaFormEvent $event
     */
    public function addCustomerCategoryFieldsForRegister(TheliaFormEvent $event)
    {
        // Retrieving CustomerCategory choices
        $customerCategoryChoices = array();

        /** @var \CustomerCategory\Model\CustomerCategory $customerCategory */
        foreach (CustomerCategoryQuery::create()->find() as $customerCategory) {
            $customerCategoryChoices[$customerCategory->getCode()] = self::trans($customerCategory->getTitle());
        }

        // Building additional fields
        $event->getForm()->getFormBuilder()
            ->add(
                self::CUSTOMER_CATEGORY_CODE_FIELD_NAME,
                'choice',
                array(
                    'constraints' => array(
                        new Constraints\Callback(
                            array(
                                'methods' => array(
                                    array(
                                        $this, 'checkCustomerCategory'
                                    )
                                )
                            )
                        ),
                        new Constraints\NotBlank(),
                    ),
                    'choices' => $customerCategoryChoices,
                    'empty_data' => false,
                    'required' => false,
                    'label' => self::trans('Customer category'),
                    'label_attr' => array(
                        'for' => 'customer_category_id',
                    ),
                    'mapped' => false,
                )
            )
        ;
    }

    /**
     * Callback used to add some fields to the Thelia's CustomerCreateForm.
     * It add two fields : one for the SIRET number and one for VAT.
     * @param TheliaFormEvent $event
     */
    public function addCustomerCategoryFieldsForUpdate(TheliaFormEvent $event)
    {
        // Adding new fields
        $customer = $this->request->getSession()->getCustomerUser();

        if (is_null($customer)) {
            // No customer => no account update => stop here
            return;
        }

        $customerCustomerCategory = CustomerCustomerCategoryQuery::create()->findOneByCustomerId($customer->getId());

        $cfData = array(
            self::CUSTOMER_CATEGORY_CODE_FIELD_NAME  => (is_null($customerCustomerCategory) or is_null($customerCustomerCategory->getCustomerCategory())) ? '' : $customerCustomerCategory->getCustomerCategory()->getCode(),
        );

        // Retrieving CustomerCategory choices
        $customerCategoryChoices = array();

        /** @var \CustomerCategory\Model\CustomerCategory $customerCategoryChoice */
        foreach (CustomerCategoryQuery::create()->find() as $customerCategoryChoice) {
            $customerCategoryChoices[$customerCategoryChoice->getCode()] = self::trans($customerCategoryChoice->getTitle());
        }


        // Building additional fields
        $event->getForm()->getFormBuilder()
            ->add(
                self::CUSTOMER_CATEGORY_CODE_FIELD_NAME,
                'choice',
                array(
                    'constraints' => array(
                        new Constraints\Callback(array('methods' => array(
                            array($this, 'checkCustomerCategory')
                        ))),
                        new Constraints\NotBlank(),
                    ),
                    'choices' => $customerCategoryChoices,
                    'empty_data' => false,
                    'required' => false,
                    'label' => self::trans('Customer category'),
                    'label_attr' => array(
                        'for' => 'customer_category_id',
                    ),
                    'mapped' => false,
                    'data' => $cfData[self::CUSTOMER_CATEGORY_CODE_FIELD_NAME],
                )
            )
        ;
    }

    /**
     * Validate a field only if the customer category is valid
     *
     * @param string                    $value
     * @param ExecutionContextInterface $context
     */
    public function checkCustomerCategory($value, ExecutionContextInterface $context)
    {
        if (CustomerCategoryQuery::create()->filterByCode($value)->count() == 0) {
            $context->addViolation(self::trans('The customer category is not valid'));
        }
    }


    /**
     * Utility for translations
     * @param $id
     * @param array $parameters
     * @return string
     */
    protected static function trans($id, array $parameters = array())
    {
        return Translator::getInstance()->trans($id, $parameters, CustomerCategory::MESSAGE_DOMAIN);
    }
}
