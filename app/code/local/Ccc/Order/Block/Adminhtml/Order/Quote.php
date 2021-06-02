<?php
class Ccc_Order_Block_Adminhtml_Order_Quote extends Mage_Adminhtml_Block_Widget_Form
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

    public function getSaveUrl()
    {
        return $this->getUrl('*/*/save');
    }
}
