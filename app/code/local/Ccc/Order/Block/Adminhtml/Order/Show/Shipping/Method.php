<?php
class Ccc_Order_Block_Adminhtml_Order_Show_Shipping_Method extends Mage_Core_Block_Template
{
    protected $order = null;

    public function setOrder(Ccc_Order_Model_Order $order)
    {
        $this->order = $order;
        return $this;
    }

    public function getOrder()
    {
        return $this->order;
    }
    
}
