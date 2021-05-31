<?php
class Ccc_Order_Block_Adminhtml_Order_Quote_Billing_Address extends Mage_Adminhtml_Block_Widget_Form
{
    protected $quote = null;

    public function getHeaderText()
    {
        return Mage::helper('order')->__('Billing Address');
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

    public function getBillingAddress()
    {
        $quote = $this->getQuote();
        $billingAddress = $quote->getBillingAddress();
        if($billingAddress->getId())
        {
            return $billingAddress;
        }
        $billingAddress = $quote->getCustomer()->getBillingAddress();
        return $billingAddress;
        
    }
    
    public function getCountry()
    {
        return Mage::getModel('directory/country')->getCollection();
    }

    public function getHeaderCssClass()
    {
        return 'head-billing-address';
    }
    
}
