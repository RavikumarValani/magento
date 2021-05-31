<?php
class Ccc_Order_Block_Adminhtml_Order_Quote_Customer extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    public function __construct()
    {
        $this->_blockGroup = 'order';
        $this->_controller = 'adminhtml_order_quote_customer';
        parent::__construct();
        $this->_removeButton('add');
        $this->_headerText = '';
    }

}
