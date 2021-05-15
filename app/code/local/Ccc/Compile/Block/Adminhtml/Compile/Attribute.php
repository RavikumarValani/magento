<?php

class Ccc_Compile_Block_Adminhtml_Compile_Attribute extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    public function __construct()
    {
    	$this->_blockGroup = 'compile';
        $this->_controller = 'adminhtml_compile_attribute';
        $this->_headerText = Mage::helper('compile')->__('Manage Attributes');
        $this->_addButtonLabel = Mage::helper('compile')->__('Add New Attribute');
        parent::__construct();
    }

}
