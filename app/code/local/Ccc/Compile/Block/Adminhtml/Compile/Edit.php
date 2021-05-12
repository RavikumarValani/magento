<?php

class Ccc_Compile_Block_Adminhtml_Compile_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'compile';
        $this->_controller = 'adminhtml_compile';
        $this->_headerText = 'Add Compile';
        parent::__construct();
    }
}
