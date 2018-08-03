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

use CustomerCategory\CustomerCategory;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Thelia\Core\Translation\Translator;
use Thelia\Form\BaseForm;
use Symfony\Component\Validator\Constraints\Callback;
use Thelia\Model\LangQuery;

/**
 * Class CustomerCategoryExportForm
 * @package CustomerCategory\Form
 */
class CustomerCategoryExportForm extends BaseForm
{
    /**
     * @return string the name of you form. This name must be unique
     */
    public function getName()
    {
        return 'customer_category_export_form';
    }

    protected function buildForm()
    {
        $this->formBuilder
            ->add(
                'productref',
                'text',
                array(
                    'constraints' => array(
                        new NotBlank()
                    ),
                    'required' => true,
                    'empty_data' => false,
                    'label' => Translator::getInstance()->trans(
                        'Product Reference',
                        array(),
                        CustomerCategory::MESSAGE_DOMAIN
                    ),
                    'label_attr' => array(
                        'for' => 'productref'
                    )
                )
            )
            ;
    }
}
