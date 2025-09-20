<?php

namespace Dinesh\ProductAttribute\Block\Adminhtml\Promo\Quote\Edit\Tab;

use Magento\Backend\Block\Widget\Form\Generic;

class CustomNote extends Generic
{
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('current_promo_quote_rule');

        $form = $this->_formFactory->create();

        $fieldset = $form->addFieldset('custom_note_fieldset', ['legend' => __('Custom Note')]);

        $fieldset->addField('custom_note', 'textarea', [
            'name' => 'custom_note',
            'label' => __('Custom Note'),
            'title' => __('Custom Note'),
            'value' => $model->getCustomNote()
        ]);

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
