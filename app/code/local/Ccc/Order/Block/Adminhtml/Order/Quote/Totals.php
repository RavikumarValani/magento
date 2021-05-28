<?php
class Ccc_Order_Block_Adminhtml_Order_Quote_Totals extends Mage_Adminhtml_Block_Widget_Form
{

    public function getSubTotal()
    {
        $sub = 0;
        $quote = Mage::getModel('order/quote');
        $items = $quote->getItems();
        foreach($items as $item)
        {
            $sub = $sub + $item['price'];
        }
        return $sub;
    }

    public function getTotal()
    {
        $sub = 0;
        $quote = Mage::getModel('order/quote');
        $items = $quote->getItems();
        foreach($items as $item)
        {
            $sub = $sub + $item['price'];
        }
        return $sub;
    }

}
