<?php
namespace Dinesh\CustomerExtension\Model\Customer;

use Magento\Customer\Model\Customer as MagentoCustomer;
use Magento\Framework\Exception\LocalizedException;

class Customer extends MagentoCustomer
{
    /**
     * Override the existing function to allow setting the password
     */
    public function setPassword($password)
    {
        // Validate password
        if (strlen($password) < 6) {
            throw new LocalizedException(__('Password should be at least 6 characters.'));
        }

        $this->_getDataModel()->setPassword($password);

        return $this;
    }
}
