<?php
class Ccc_Order_Block_Adminhtml_Order_Quote_Form extends Mage_Adminhtml_Block_Widget_Form
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('order_customer_form');
    }

    public function getHeaderText()
    {
        return Mage::helper('order')->__('Please Select a Customer');
    }

}
