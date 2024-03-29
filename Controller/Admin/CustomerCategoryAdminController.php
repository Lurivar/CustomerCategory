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

namespace CustomerCategory\Controller\Admin;

use CustomerCategory\Action\CustomerCategoryExportByProduct;
use CustomerCategory\Action\ExportFamilies;
use CustomerCategory\CustomerCategory;
use CustomerCategory\Event\CustomerCustomerCategoryEvent;
use CustomerCategory\Event\CustomerCategoryEvent;
use CustomerCategory\Event\CustomerCategoryEvents;
use CustomerCategory\Form\CustomerCustomerCategoryForm;
use CustomerCategory\Form\CustomerCategoryCreateForm;
use CustomerCategory\Form\CustomerCategoryDeleteForm;
use CustomerCategory\Form\CustomerCategoryExportForm;
use CustomerCategory\Form\CustomerCategoryUpdateForm;
use CustomerCategory\Model\CustomerCategoryQuery;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\HttpFoundation\JsonResponse;
use Thelia\Core\HttpFoundation\Request;
use Thelia\Core\Security\AccessManager;
use Thelia\Core\Security\Resource\AdminResources;
use Thelia\Core\Translation\Translator;
use Thelia\Form\BaseForm;
use Thelia\Form\CustomerUpdateForm;
use Thelia\Form\Exception\FormValidationException;
use Thelia\Model\Base\CustomerQuery;
use Thelia\Model\Customer;
use Thelia\Model\ProductQuery;
use Thelia\Tools\URL;

/**
 * Class CustomerCategoryAdminController
 * @package CustomerCategory\Controller\Admin
 */
class CustomerCategoryAdminController extends BaseAdminController
{
    /**
     * @param Request $request
     * @return mixed|\Thelia\Core\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        if (null !== $response = $this->checkAuth(array(AdminResources::MODULE), array('CustomerCategory'), AccessManager::CREATE)) {
            return $response;
        }

        $error = "";
        $form = new CustomerCategoryCreateForm($request);

        try {
            $formValidate = $this->validateForm($form);

            $event = new CustomerCategoryEvent();
            $event->hydrateByForm($formValidate);

            $this->dispatch(CustomerCategoryEvents::CUSTOMER_CATEGORY_CREATE, $event);

        } catch (\Exception $e) {
            $error = $e->getMessage();
        }

        $message = Translator::getInstance()->trans(
            "Customer category was created successfully",
            array(),
            CustomerCategory::MODULE_DOMAIN
        );

        return self::renderAdminConfig($form, $message, $error);
    }

    /**
     * @param Request $request
     * @param $id
     * @return mixed|\Thelia\Core\HttpFoundation\Response
     */
    public function exportAction(Request $request, $id)
    {
        if (null !== $response = $this->checkAuth(array(AdminResources::MODULE), array('CustomerCategory'), AccessManager::VIEW)) {
            return $response;
        }

        $error = "";
        $form = new CustomerCategoryExportForm($request);

        try {
            $formValidate = $this->validateForm($form);

            $customerCategory = CustomerCategoryQuery::create()->findPk($id);

            if ($customerCategory === null) {
                throw new \Exception("Customer Category not found by Id");
            }

            $productref = $formValidate->get("productref");
            $productref = $productref->getNormData();
            $product = ProductQuery::create()->filterByRef($productref)->findOne();

            if ($product === null) {
                throw new \Exception("Product not found");
            }

            $exportCategory = new CustomerCategoryExportByProduct();
            $exportCategory->exportCategoryAction($id, $productref);

        } catch (\Exception $e) {
            $error = $e->getMessage();
        }

        $message = Translator::getInstance()->trans(
            "Customer category was exported successfully",
            array(),
            CustomerCategory::MODULE_DOMAIN
        );

        return self::renderAdminConfig($form, $message, $error);
    }

    /**
     * @param Request $request
     * @param $id
     * @return mixed|\Thelia\Core\HttpFoundation\Response
     */
    public function updateAction(Request $request, $id)
    {
        if (null !== $response = $this->checkAuth(array(AdminResources::MODULE), array('CustomerCategory'), AccessManager::UPDATE)) {
            return $response;
        }

        $error = "";
        $form = new CustomerCategoryUpdateForm($request);

        try {
            $formValidate = $this->validateForm($form);

            $customerCategory = CustomerCategoryQuery::create()->findPk($id);

            if ($customerCategory === null) {
                throw new \Exception("Customer Category not found by Id");
            }

            $event = new CustomerCategoryEvent($customerCategory);
            $event->hydrateByForm($formValidate);

            $this->dispatch(CustomerCategoryEvents::CUSTOMER_CATEGORY_UPDATE, $event);

        } catch (\Exception $e) {
            $error = $e->getMessage();
        }

        $message = Translator::getInstance()->trans(
            "Customer category was updated successfully",
            array(),
            CustomerCategory::MODULE_DOMAIN
        );

        return self::renderAdminConfig($form, $message, $error);
    }

    /**
     * Update default category
     * There must be at least one default category
     *
     * @return mixed|\Symfony\Component\HttpFoundation\Response|static
     */
    public function updateDefaultAction()
    {
        if (null !== $response = $this->checkAuth([AdminResources::MODULE], ['CustomerCategory'], AccessManager::UPDATE)) {
            return $response;
        }

        $error = null;
        $ex = null;
        $form = $this->createForm('customer_category_update_default_form');

        try {
            $vForm = $this->validateForm($form);

            // Get customer_category to update
            $customerCategory = CustomerCategoryQuery::create()->findOneById($vForm->get('customer_category_id')->getData());

            // If the customer_category exists
            if (null !== $customerCategory) {
                // If the category to update is not already the default one
                if (!$customerCategory->getIsDefault()) {
                    // Remove old default category
                    if (null !== $defaultCustomerFamilies = CustomerCategoryQuery::create()->findByIsDefault(1)) {
                        /** @var \CustomerCategory\Model\CustomerCategory $defaultCustomerCategory */
                        foreach ($defaultCustomerFamilies as $defaultCustomerCategory) {
                            $defaultCustomerCategory
                                ->setIsDefault(0)
                                ->save();
                        }
                    }
                    // Save new default category
                    $customerCategory
                        ->setIsDefault(1)
                        ->save();
                }
            }

        } catch (FormValidationException $ex) {
            $error = $this->createStandardFormValidationErrorMessage($ex);
        } catch (\Exception $ex) {
            $error = $ex->getMessage();
        }

        if ($error !== null) {
            $this->setupFormErrorContext(
                $this->getTranslator()->trans("Error updating default category", [], CustomerCategory::MODULE_DOMAIN),
                $error,
                $form,
                $ex
            );
            return JsonResponse::create(['error'=>$error], 500);
        }

        return RedirectResponse::create(URL::getInstance()->absoluteUrl("/admin/module/CustomerCategory"));

    }

    /**
     * @param Request $request
     * @param $id
     * @return mixed|\Thelia\Core\HttpFoundation\Response
     */
    public function deleteAction(Request $request, $id)
    {
        if (null !== $response = $this->checkAuth(array(AdminResources::MODULE), array('CustomerCategory'), AccessManager::DELETE)) {
            return $response;
        }

        $error = "";
        $form = new CustomerCategoryDeleteForm($request);

        try {
            $formValidate = $this->validateForm($form);

            $customerCategory = CustomerCategoryQuery::create()->findPk($id);

            if ($customerCategory === null) {
                throw new \Exception("Customer Category not found by Id");
            }

            $event = new CustomerCategoryEvent($customerCategory);

            $this->dispatch(CustomerCategoryEvents::CUSTOMER_CATEGORY_DELETE, $event);

        } catch (\Exception $e) {
            $error = $e->getMessage();
        }

        $message = Translator::getInstance()->trans(
            "Customer category was deleted successfully",
            array(),
            CustomerCategory::MODULE_DOMAIN
        );

        return self::renderAdminConfig($form, $message, $error);
    }

    /**
     * @param BaseForm $form
     * @param string $successMessage
     * @param string $errorMessage
     * @return \Symfony\Component\HttpFoundation\Response|static
     */
    protected function renderAdminConfig($form, $successMessage, $errorMessage)
    {
        if (!empty($errorMessage)) {
            $form->setErrorMessage($errorMessage);

            $this->getParserContext()
                ->addForm($form)
                ->setGeneralError($errorMessage);
        }

        //for compatibility 2.0
        if (method_exists($this->getSession(), "getFlashBag")) {
            if (empty($errorMessage)) {
                $this->getSession()->getFlashBag()->add("success", $successMessage);
            } else {
                $this->getSession()->getFlashBag()->add("danger", $errorMessage);
            }
        }

        return RedirectResponse::create(
            URL::getInstance()->absoluteUrl("/admin/module/CustomerCategory")
        );
    }

    /**
     * @param Request $request
     * @return mixed|\Thelia\Core\HttpFoundation\Response
     */
    public function customerUpdateAction(Request $request)
    {
        if (null !== $response = $this->checkAuth(array(AdminResources::MODULE), array('CustomerCategory'), AccessManager::UPDATE)) {
            return $response;
        }

        $error = "";
        $form = new CustomerCustomerCategoryForm($request);
        try {
            $formValidate = $this->validateForm($form);
            $event = new CustomerCustomerCategoryEvent($formValidate->get('customer_id')->getData());
            $event
                ->setCustomerCategoryId($formValidate->get('customer_category_id')->getData())
                ->setSiret($formValidate->get('siret')->getData())
                ->setVat($formValidate->get('vat')->getData())
            ;

            $this->dispatch(CustomerCategoryEvents::CUSTOMER_CUSTOMER_CATEGORY_UPDATE, $event);

            $this->generateRedirect(URL::getInstance()->absoluteUrl(
                '/admin/customer/update?customer_id='.$formValidate->get('customer_id')->getData()
            ));
        } catch (FormValidationException $ex) {
            $error = $this->createStandardFormValidationErrorMessage($ex);
        } catch (\Exception $e) {
            $error = $e->getMessage();
        }

        if (!empty($error)) {
            $form->setErrorMessage($error);
        }

        $this->getParserContext()
            ->addForm($form)
            ->setGeneralError($error);

        //Don't forget to fill the Customer form
        $customerId = $request->get('customer_customer_category_form')['customer_id'];
        if (null != $customer = CustomerQuery::create()->findPk($customerId)) {
            $customerForm = $this->hydrateCustomerForm($customer);
            $this->getParserContext()->addForm($customerForm);
        }

        return $this->render('customer-edit', array(
                'customer_id' => $request->get('customer_customer_category_form')['customer_id'],
                "order_creation_error" => Translator::getInstance()->trans($error, array(), CustomerCategory::MESSAGE_DOMAIN)
            ));
    }

    /**
     * @param Customer $customer
     * @return CustomerUpdateForm
     */
    protected function hydrateCustomerForm(Customer $customer)
    {
        // Get default adress of the customer
        $address = $customer->getDefaultAddress();

        // Prepare the data that will hydrate the form
        $data = array(
            'id'        => $customer->getId(),
            'firstname' => $customer->getFirstname(),
            'lastname'  => $customer->getLastname(),
            'email'     => $customer->getEmail(),
            'title'     => $customer->getTitleId(),
            'discount'  => $customer->getDiscount(),
            'reseller'  => $customer->getReseller(),
        );

        if ($address !== null) {
            $data['company']   = $address->getCompany();
            $data['address1']  = $address->getAddress1();
            $data['address2']  = $address->getAddress2();
            $data['address3']  = $address->getAddress3();
            $data['phone']     = $address->getPhone();
            $data['cellphone'] = $address->getCellphone();
            $data['zipcode']   = $address->getZipcode();
            $data['city']      = $address->getCity();
            $data['country']   = $address->getCountryId();
        }

        // A loop is used in the template
        return new CustomerUpdateForm($this->getRequest(), 'form', $data);
    }
}
