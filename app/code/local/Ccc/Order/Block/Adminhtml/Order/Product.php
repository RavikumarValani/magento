<?php
class Ccc_Order_Block_Adminhtml_Order_Product extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        $this->_objectId = 'order_id';
        $this->_blockGroup = 'order';
        $this->_controller = 'adminhtml_order';
        $this->_mode = 'product';

        parent::__construct();
        $this->_updateButton('save', 'label', Mage::helper('order')->__('Submit Order'));
        $this->_updateButton('save', 'onclick', "changeAction()");
        $this->setId('product_order_create');

    }

    
}
