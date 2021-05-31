<?php
class Ccc_Order_Block_Adminhtml_Order_Quote_Product extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    public function __construct()
    {
        parent::__construct();
        $this->_controller = 'adminhtml_order_quote_product';
        $this->_blockGroup = 'order';
        $this->setId('order_product');
        $this->_headerText = '';
        $this->removeButton('add');
        $this->_addButton('add_product', array(
            'label'     => Mage::helper('sales')->__('Add Selected Product(s)'),
            'onclick'   => "addItemSelect()",
            'class'     => '',
        ));
    }

    public function getHeaderText()
    {
        return Mage::helper('order')->__('Select Product(s) to Add');
    }
}
