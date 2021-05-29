<?php
class Ccc_Order_Block_Adminhtml_Order_Quote_Totals extends Mage_Adminhtml_Block_Widget_Form
{
    protected $quote = null;

    public function setQuote(Ccc_Order_Model_Quote $quote)
    {
        $this->quote = $quote;
        return $this;
    }

    public function getQuote()
    {
        return $this->quote;
    }

    public function getShippingAmount()
    {
        $quote = $this->getQuote();
        return $quote->getShippingAmount();
    }
    
    public function getTotal()
    {
        return ($this->getQuote()->getSubTotal() + $this->getShippingAmount());
    }
}
