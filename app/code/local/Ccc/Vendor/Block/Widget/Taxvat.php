<?php
class Ccc_Vendor_Block_Widget_Taxvat extends Mage_Customer_Block_Widget_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->setTemplate('vendor/widget/taxvat.phtml');
    }

    public function isEnabled()
    {
        return (bool)$this->_getAttribute('taxvat')->getIsVisible();
    }

    public function isRequired()
    {
        return (bool)$this->_getAttribute('taxvat')->getIsRequired();
    }

    public function getCustomer()
    {
        return Mage::getSingleton('vendor/session')->getCustomer();
    }
}
