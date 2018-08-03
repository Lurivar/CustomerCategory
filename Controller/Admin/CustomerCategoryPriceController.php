<?php

namespace CustomerCategory\Controller\Admin;

use CustomerCategory\CustomerCategory;
use CustomerCategory\Model\CustomerCategoryPrice;
use CustomerCategory\Model\CustomerCategoryPriceQuery;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Thelia\Controller\Admin\BaseAdminController;
use Thelia\Core\Security\AccessManager;
use Thelia\Core\Security\Resource\AdminResources;
use Thelia\Form\Exception\FormValidationException;
use Thelia\Tools\URL;

/**
 * Class CustomerCategoryPriceController
 * @package CustomerCategory\Controller
 * @author Etienne Perriere <eperriere@openstudio.fr>
 */
class CustomerCategoryPriceController extends BaseAdminController
{
    /**
     * Add or update amounts and factor to calculate prices for customer families
     *
     * @return mixed|\Symfony\Component\HttpFoundation\Response|\Thelia\Core\HttpFoundation\Response|static
     */
    public function updateAction()
    {
        // Check rights
        if (null !== $response = $this->checkAuth(
                [AdminResources::MODULE],
                ['CustomerCategory'],
                [AccessManager::VIEW, AccessManager::CREATE, AccessManager::UPDATE]
            )) {
            return $response;
        }

        $form = $this->createForm('customer_category_price_update');
        $error = null;
        $ex = null;

        try {
            $vForm = $this->validateForm($form);

            // If no entry exists for the given CustomerCategoryId & promo, create it
            if (null === $customerCategoryPrice = CustomerCategoryPriceQuery::create()
                    ->findPk([$vForm->get('customer_category_id')->getData(), $vForm->get('promo')->getData()])) {
                // Create new CustomerCategoryPrice
                $customerCategoryPrice = new CustomerCategoryPrice();
                $customerCategoryPrice
                    ->setCustomerCategoryId($vForm->get('customer_category_id')->getData())
                    ->setPromo($vForm->get('promo')->getData());
            }

            // Save data
            $customerCategoryPrice
                ->setUseEquation($vForm->get('use_equation')->getData())
                ->setAmountAddedBefore($vForm->get('amount_added_before')->getData())
                ->setAmountAddedAfter($vForm->get('amount_added_after')->getData())
                ->setMultiplicationCoefficient($vForm->get('coefficient')->getData())
                ->setIsTaxed($vForm->get('is_taxed')->getData())
                ->save();

        } catch (FormValidationException $ex) {
            $error = $this->createStandardFormValidationErrorMessage($ex);
        } catch (\Exception $ex) {
            $error = $ex->getMessage();
        }

        if ($error !== null) {
            $this->setupFormErrorContext(
                $this->getTranslator()->trans("CustomerCategory configuration", [], CustomerCategory::MODULE_DOMAIN),
                $error,
                $form,
                $ex
            );
            return $this->render('module-configure', ['module_code' => 'CustomerCategory']);
        }

        return RedirectResponse::create(URL::getInstance()->absoluteUrl("/admin/module/CustomerCategory"));
    }
}