<?php
class Ccc_Order_Block_Adminhtml_Order_Quote_Billing_Method extends Mage_Adminhtml_Block_Widget_Form
{
    protected $quote = null;

    public function __construct()
    {
        parent::__construct();
        $this->setId('order_create_billing_method');
    }

    public function getHeaderText()
    {
        return Mage::helper('order')->__('Payment Method');
    }

    public function getHeaderCssClass()
    {
        return 'head-payment-method';
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

    public function getPayemntMethodTitle()
    {
    	$methods = Mage::getModel('payment/config');
    	$activemethod = $methods->getActiveMethods();
    	unset($activemethod['paypal_billing_agreement']);
    	unset($activemethod['checkmo']);
    	unset($activemethod['free']);
    	return $activemethod;
    }

    public function getCode()
    {
        return $this->getQuote()->getBillingMethod();
    }
}
