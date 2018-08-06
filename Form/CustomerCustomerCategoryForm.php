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
        ;
    }
}
