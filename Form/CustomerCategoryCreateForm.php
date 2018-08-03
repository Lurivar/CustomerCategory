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
 * Class CustomerCategoryCreateForm
 * @package CustomerCategory\Form
 */
class CustomerCategoryCreateForm extends BaseForm
{
    /**
     * @return string the name of you form. This name must be unique
     */
    public function getName()
    {
        return 'customer_category_create_form';
    }

    /**
     * @param $value
     * @param ExecutionContextInterface $context
     */
    public function checkLocale($value, ExecutionContextInterface $context)
    {
        if (!LangQuery::create()->findOneByCode($value) === null) {
            $context->addViolation(Translator::getInstance()->trans(
                "Invalid locale"
            ));
        }
    }

    protected function buildForm()
    {
        $this->formBuilder
            ->add(
                'code',
                'text',
                array(
                    'constraints' => array(
                        new NotBlank()
                    ),
                    'required' => true,
                    'empty_data' => false,
                    'label' => Translator::getInstance()->trans(
                        'Code',
                        array(),
                        CustomerCategory::MESSAGE_DOMAIN
                    ),
                    'label_attr' => array(
                        'for' => 'code'
                    )
                )
            )
            ->add(
                'title',
                'text',
                array(
                    'constraints' => array(
                        new NotBlank()
                    ),
                    'required' => true,
                    'empty_data' => false,
                    'label' => Translator::getInstance()->trans(
                        'Title'
                    ),
                    'label_attr' => array(
                        'for' => 'title'
                    )
                )
            )
            ->add(
                'locale',
                'text',
                array(
                    'constraints' => array(
                        new NotBlank(),
                        new Callback(array("methods" => array(
                            array($this, "checkLocale")
                        )))
                    ),
                    'required' => true,
                    'empty_data' => false,
                    'label' => Translator::getInstance()->trans(
                        'Locale'
                    ),
                    'label_attr' => array(
                        'for' => 'locale'
                    )
                )
            );
    }
}
