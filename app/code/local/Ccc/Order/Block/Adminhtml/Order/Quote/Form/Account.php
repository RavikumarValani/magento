<?php
class Ccc_Order_Block_Adminhtml_Order_Quote_Form_Account extends Mage_Adminhtml_Block_Widget_Form
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('order_quote_form');
    }

    protected function _getSession()
    {
        return Mage::getSingleton('order/session_quote');
    }

    public function getCustometData()
    {
        $customer = Mage::getModel('customer/customer');
        $customer->load($this->_getSession()->getCustomerId());
        return $customer->getData();
    }

}
