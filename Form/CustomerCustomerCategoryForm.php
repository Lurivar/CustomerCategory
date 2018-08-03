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

namespace CustomerCategory\Form;

use Symfony\Component\Validator\Constraints;
use CustomerCategory\CustomerCategory;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Thelia\Core\Translation\Translator;
use Thelia\Form\BaseForm;

/**
 * Class CustomerCustomerCategoryForm
 * @package CustomerCategory\Form
 */
class CustomerCustomerCategoryForm extends BaseForm
{
    /**
     * @return string the name of you form. This name must be unique
     */
    public function getName()
    {
        return 'customer_customer_category_form';
    }

    /**
     * Validate a field only if customer category is professional
     *
     * @param string                    $value
     * @param ExecutionContextInterface $context
     */
    public function checkProfessionalInformation($value, ExecutionContextInterface $context)
    {
        $customerCategory = CustomerCategory::getCustomerCategoryByCode(CustomerCategory::CUSTOMER_CATEGORY_PROFESSIONAL);

        if (null != $form = $this->getRequest()->request->get("customer_customer_category_form")) {
            if (array_key_exists("customer_category_id", $form) &&
                $form["customer_category_id"] == $customerCategory->getId()) {
                if (strlen($value) <= 1) {
                    $context->addViolation(Translator::getInstance()->trans(
                        "This field can't be empty",
                        array(),
                        CustomerCategory::MESSAGE_DOMAIN
                    ));
                }
            }
        }
    }

    protected function buildForm()
    {
        $this->formBuilder
            ->add('customer_id', 'integer', array(
                    'constraints' => array(
                        new Constraints\NotBlank()
                    ),
                    'label' => Translator::getInstance()->trans(
                        'Customer',
                        array(),
                        CustomerCategory::MESSAGE_DOMAIN
                    ),
                    'label_attr' => array(
                        'for' => 'customer_id'
                    )
                ))
            ->add('customer_category_id', 'text', array(
                    'constraints' => array(
                        new Constraints\NotBlank()
                    ),
                    'label' => Translator::getInstance()->trans(
                        'Customer category',
                        array(),
                        CustomerCategory::MESSAGE_DOMAIN
                    ),
                    'label_attr' => array(
                        'for' => 'customer_id'
                    )
                ))
            ->add(
                'siret',
                'text',
                array(
                    /*
                    'constraints' => array(
                        new Constraints\Callback(array("methods" => array(
                            array($this, "checkProfessionalInformation")
                        )))
                    ),*/
                    'required' => false,
                    'empty_data' => false,
                    'label' => Translator::getInstance()->trans(
                        'Siret number',
                        array(),
                        CustomerCategory::MESSAGE_DOMAIN
                    ),
                    'label_attr' => array(
                        'for' => 'siret'
                    )
                )
            )
            ->add(
                'vat',
                'text',
                array(
                    /*
                    'constraints' => array(
                        new Constraints\Callback(array("methods" => array(
                            array($this, "checkProfessionalInformation")
                        )))
                    ),*/
                    'required' => false,
                    'empty_data' => false,
                    'label' => Translator::getInstance()->trans(
                        'Vat',
                        array(),
                        CustomerCategory::MESSAGE_DOMAIN
                    ),
                    'label_attr' => array(
                        'for' => 'vat'
                    )
                )
            )
        ;
    }
}
