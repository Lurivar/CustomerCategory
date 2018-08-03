<?php

namespace CustomerCategory\Form;

use Thelia\Form\BaseForm;

/**
 * Class CustomerCategoryUpdateDefaultForm
 * @package CustomerCategory\Form
 */
class CustomerCategoryUpdateDefaultForm extends BaseForm
{
    public function getName()
    {
        return 'customer_category_update_default_form';
    }

    protected function buildForm()
    {
        $this->formBuilder
            ->add(
                'customer_category_id',
                'integer'
            );
    }
}