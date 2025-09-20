<?php
namespace Dinesh\ProductAttribute\Plugin;

class SaveCustomNote
{
    public function beforeSave(\Magento\SalesRule\Model\Rule $subject)
    {
        $customNote = $subject->getData('custom_note');
        $subject->setCustomNote($customNote);
    }
}
