<?php
class Ccc_Order_Block_Adminhtml_Order_Quote_Shipping_Method extends Mage_Adminhtml_Block_Widget_Form
{
    protected $quote = null;

    public function __construct()
    {
        parent::__construct();
        $this->setId('order_create_shipping_method');
    }

    public function getHeaderText()
    {
        return Mage::helper('order')->__('Shipping Method');
    }

    public function getHeaderCssClass()
    {
        return 'head-shipping-method';
    }

    public function getShippingMethods()
 	{
 		$shippingMethods = Mage::getModel('shipping/config')->getActiveCarriers(); 
 		return $shippingMethods;
 	}

     public function setQuote(Ccc_Order_Model_Quote $quote)
     {
         $this->quote = $quote;
         return $this;
     }
 
     public function getQuote()
     {
         return $this->quote;
     }
 
     public function getShippingCode()
     {
        return $this->getQuote()->getShippingMethodCode();
     }

}
