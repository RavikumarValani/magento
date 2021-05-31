<?php
class Ccc_Order_Block_Adminhtml_Order_Show extends Mage_Adminhtml_Block_Widget_Form
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
