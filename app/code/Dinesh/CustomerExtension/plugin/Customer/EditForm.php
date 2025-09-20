<?php
namespace Dinesh\CustomerExtension\Plugin\Customer;

use Magento\Customer\Block\Adminhtml\Customer\Edit\Form as CustomerForm;
use Magento\Framework\View\Element\UiComponent\UiComponentInterface;
use Magento\Framework\View\Element\UiComponentFactory;

class EditForm
{
    /**
     * Modify customer form to allow password to be set
     */
    public function beforeToHtml(CustomerForm $subject)
    {
        $form = $subject->getForm();
        $passwordField = $form->getElement('password');
        
        // Set password field to allow input
        if ($passwordField) {
            $passwordField->setDisabled(false);
        }

        return [$form];
    }
}
