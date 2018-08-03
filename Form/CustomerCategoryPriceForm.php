<?php

namespace CustomerCategory\Form;

use Thelia\Form\BaseForm;

/**
 * Class CustomerCategoryPriceForm
 * @package CustomerCategory\Form
 * @author Etienne Perriere <eperriere@openstudio.fr>
 */
class CustomerCategoryPriceForm extends BaseForm
{
    public function getName()
    {
        return 'customer_category_price_update';
    }

    protected function buildForm()
    {
        $this->formBuilder
            ->add(
                'customer_category_id',
                'integer'
            )
            ->add(
                'promo',
                'integer'
            )
            ->add(
                'use_equation',
                'checkbox',
                []
            )
            ->add(
                'amount_added_before',
                'number',
                [
                    'precision' => 6,
                    'required' => false
                ]
            )
            ->add(
                'amount_added_after',
                'number',
                [
                    'precision' => 6,
                    'required' => false
                ]
            )
            ->add(
                'coefficient',
                'number',
                [
                    'precision' => 6,
                    'required' => false
                ]
            )
            ->add(
                'is_taxed',
                'checkbox',
                []
            )
        ;
    }
}