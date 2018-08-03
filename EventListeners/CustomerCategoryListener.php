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
use CustomerCategory\Event\CustomerCustomerCategoryEvent;
use CustomerCategory\Event\CustomerCategoryEvent;
use CustomerCategory\Event\CustomerCategoryEvents;
use CustomerCategory\Model\CustomerCustomerCategory;
use CustomerCategory\Model\CustomerCustomerCategoryQuery;
use CustomerCategory\Model\CustomerCategoryQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Thelia\Core\Event\Customer\CustomerCreateOrUpdateEvent;
use Thelia\Core\Event\Customer\CustomerEvent;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Core\HttpFoundation\Request;
use Thelia\Core\Template\ParserInterface;
use Thelia\Mailer\MailerFactory;

/**
 * Class CustomerCategoryListener
 * @package CustomerCategory\EventListeners
 */
class CustomerCategoryListener implements EventSubscriberInterface
{
    const THELIA_CUSTOMER_CREATE_FORM_NAME = 'thelia_customer_create';
    const THELIA_CUSTOMER_UPDATE_FORM_NAME = 'thelia_customer_profile_update';

    /** @var \Thelia\Core\HttpFoundation\Request */
    protected $request;

    /** @var \Thelia\Core\Template\ParserInterface */
    protected $parser;

    /** @var \Thelia\Mailer\MailerFactory */
    protected $mailer;

    /**
     * @param Request $request
     * @param ParserInterface $parser
     * @param MailerFactory $mailer
     */
    public function __construct(Request $request, ParserInterface $parser, MailerFactory $mailer)
    {
        $this->request = $request;
        $this->parser = $parser;
        $this->mailer = $mailer;
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
            TheliaEvents::AFTER_CREATECUSTOMER => array(
                'afterCreateCustomer', 100
            ),
            TheliaEvents::CUSTOMER_UPDATEPROFILE => array(
                'customerUpdateProfile', 100
            ),
            CustomerCategoryEvents::CUSTOMER_CUSTOMER_CATEGORY_UPDATE => array(
                "customerCustomerCategoryUpdate", 128
            ),
            CustomerCategoryEvents::CUSTOMER_CATEGORY_CREATE => array(
                'create', 128
            ),
            CustomerCategoryEvents::CUSTOMER_CATEGORY_UPDATE => array(
                'update', 128
            ),
            CustomerCategoryEvents::CUSTOMER_CATEGORY_DELETE => array(
                'delete', 128
            ),
        );
    }

    /**
     * @param CustomerCategoryEvent $event
     * @throws \Exception
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function create(CustomerCategoryEvent $event)
    {
        if (CustomerCategoryQuery::create()
            ->filterByCode($event->getCode())
            ->findOne() !== null
        ) {
            throw new \Exception("Customer category code is already in use");
        }

        $event->getCustomerCategory()->save();
    }

    /**
     * @param CustomerCategoryEvent $event
     * @throws \Exception
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function update(CustomerCategoryEvent $event)
    {
        if (CustomerCategoryQuery::create()
                ->filterByCode($event->getCode())
                ->filterById($event->getId(), Criteria::NOT_EQUAL)
                ->findOne() !== null
        ) {
            throw new \Exception("Customer category code is already in use");
        }

        $event->getCustomerCategory()->save();
    }

    /**
     * @param CustomerCategoryEvent $event
     */
    public function delete(CustomerCategoryEvent $event)
    {
        $event->getCustomerCategory()->delete();
    }

    /**
     * @param CustomerEvent $event
     * @param $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function afterCreateCustomer(CustomerEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        $form = $this->request->request->get(self::THELIA_CUSTOMER_CREATE_FORM_NAME);

        if (is_null($form) or !array_key_exists(CustomerCategoryFormListener::CUSTOMER_CATEGORY_CODE_FIELD_NAME, $form)) {
            // Nothing to create the new CustomerCustomerCategory => stop here !
            return;
        }

        $customerCategory = CustomerCategoryQuery::create()->findOneByCode($form[CustomerCategoryFormListener::CUSTOMER_CATEGORY_CODE_FIELD_NAME]);

        if (is_null($customerCategory)) {
            // No category => no CustomerCustomerCategory to update.
            return;
        }

        $customerCategoryId = $customerCategory->getId();

        // Ignore SIRET and VAT if the customer is not professional
        $siret = $customerCategory->getCode() == CustomerCategory::CUSTOMER_CATEGORY_PROFESSIONAL ? $form[CustomerCategoryFormListener::CUSTOMER_CATEGORY_SIRET_FIELD_NAME] : '';
        $vat = $customerCategory->getCode() == CustomerCategory::CUSTOMER_CATEGORY_PROFESSIONAL ? $form[CustomerCategoryFormListener::CUSTOMER_CATEGORY_VAT_FIELD_NAME] : '';

        $updateEvent = new CustomerCustomerCategoryEvent($event->getCustomer()->getId());
        $updateEvent
            ->setCustomerCategoryId($customerCategoryId)
            ->setSiret($siret)
            ->setVat($vat)
        ;

        $dispatcher->dispatch(CustomerCategoryEvents::CUSTOMER_CUSTOMER_CATEGORY_UPDATE, $updateEvent);
    }

    /**
     * @param CustomerCreateOrUpdateEvent $event
     * @param $eventName
     * @param EventDispatcherInterface $dispatcher
     */
    public function customerUpdateProfile(CustomerCreateOrUpdateEvent $event, $eventName, EventDispatcherInterface $dispatcher)
    {
        $form = $this->request->request->get(self::THELIA_CUSTOMER_UPDATE_FORM_NAME);

        if (is_null($form) or !array_key_exists(CustomerCategoryFormListener::CUSTOMER_CATEGORY_CODE_FIELD_NAME, $form)) {
            // Nothing to update => stop here !
            return;
        }

        // Erase SIRET and VAT if the customer is now in the 'particular' customer category.
        if ($form[CustomerCategoryFormListener::CUSTOMER_CATEGORY_CODE_FIELD_NAME] == CustomerCategory::CUSTOMER_CATEGORY_PARTICULAR) {
            $siret = '';
            $vat = '';
        } else {
            $siret = $form[CustomerCategoryFormListener::CUSTOMER_CATEGORY_SIRET_FIELD_NAME];
            $vat = $form[CustomerCategoryFormListener::CUSTOMER_CATEGORY_VAT_FIELD_NAME];
        }

        $newCustomerCategory = CustomerCategoryQuery::create()->findOneByCode($form[CustomerCategoryFormListener::CUSTOMER_CATEGORY_CODE_FIELD_NAME]);


        $updateEvent = new CustomerCustomerCategoryEvent($event->getCustomer()->getId());
        $updateEvent
            ->setCustomerCategoryId($newCustomerCategory->getId())
            ->setSiret($siret)
            ->setVat($vat)
        ;

        $dispatcher->dispatch(CustomerCategoryEvents::CUSTOMER_CUSTOMER_CATEGORY_UPDATE, $updateEvent);
    }

    /**
     * @param CustomerCustomerCategoryEvent $event
     */
    public function customerCustomerCategoryUpdate(CustomerCustomerCategoryEvent $event)
    {
        $customerCustomerCategory = CustomerCustomerCategoryQuery::create()->findOneByCustomerId($event->getCustomerId());

        if ($customerCustomerCategory === null) {
            $customerCustomerCategory = new CustomerCustomerCategory();
            $customerCustomerCategory
                ->setCustomerId($event->getCustomerId())
            ;
        }

        $categoryIds = implode(",", $event->getCustomerCategoryId());

        $customerCustomerCategory
            ->setCustomerCategoryId($categoryIds)
            ->setSiret($event->getSiret())
            ->setVat($event->getVat())
            ->save()
        ;
    }

    /**
     * @return mixed|null
     */
    protected function getCustomerCategoryForm()
    {
        if (null != $form = $this->request->request->get("customer_category_customer_profile_update_form")) {
            return $form;
        }

        if (null != $form = $this->request->request->get(self::THELIA_CUSTOMER_CREATE_FORM_NAME)) {
            return $form;
        }

        return null;
    }
}
