<?php
class Ccc_Order_Block_Adminhtml_Order_Quote_Items_Grid extends Mage_Core_Block_Template
{
    protected $quote;

    public function setQuote(Ccc_Order_Model_Quote $quote)
    {
        $this->quote = $quote;
        return $this;
    }

    public function getQuote()
    {
        return $this->quote;
    }

    public function getHeaderText()
    {
        return Mage::helper('order')->__('Ordered Items');
    }

}
