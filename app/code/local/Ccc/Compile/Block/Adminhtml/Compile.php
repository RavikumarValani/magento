<?php
class Ccc_Compile_Block_Adminhtml_Compile extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'compile';
        $this->_controller = 'adminhtml_compile';
        $this->_headerText = $this->__('compile Grid');
        parent::__construct();
    }
}
