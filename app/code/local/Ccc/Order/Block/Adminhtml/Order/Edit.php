<?php
class Ccc_Order_Block_Adminhtml_Order_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
        $this->_controller = 'adminhtml_order';
        $this->_blockGroup = 'ccc_order';
        $this->_headerText = $this->__('Edit Order');
    }
}
