<?php 
    class Ccc_Test_Block_Adminhtml_Test extends Mage_Adminhtml_Block_Widget_Grid_Container
    {
        public function __construct()
        {
            $this->_blockGroup = 'test';
            $this->_controller = 'adminhtml_test';
            $this->_headerText = 'Test Grid';
            parent::__construct();
        }
    }
?>
