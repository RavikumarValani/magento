<?php 
    class Ccc_Test_Block_Adminhtml_Test_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
    {
        public function __construct()
        {
            $this->_blockGroup = 'test';
            $this->_controller = 'adminhtml_test';
            $this->_headerText = $this->__('Edit Test');
            parent::__construct();
        }
    }
?>
