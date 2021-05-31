<?php
class Ccc_Order_Block_Adminhtml_Order_Quote_Shipping_Address extends Mage_Adminhtml_Block_Widget_Form
{
    protected $quote = null;

    public function getHeaderText()
    {
        return Mage::helper('sales')->__('Shipping Address');
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

    public function getShippingAddress()
    {
        $quote = $this->getQuote();
        $shippingAddress = $quote->getShippingAddress();
        if($shippingAddress->getId())
        {
            return $shippingAddress;
        }
        $shippingAddress = $quote->getCustomer()->getShippingAddress();
        return $shippingAddress;
        
    }
    
    public function getCountry()
    {
        return Mage::getModel('directory/country')->getCollection();
    }
    
}
