<?php 
    class Ccc_Test_Block_Adminhtml_Test_Grid extends Mage_Adminhtml_Block_Widget_Grid
    {
        public function __construct() {
            $this->setId('testGrid');
            $this->setDefaultSort('test_id');
            $this->setDefaultDir('asc');
            $this->setSaveParametersInSession(true);
            parent::__construct();
        }

        public function _prepareCollection()
        {
            $collection = Mage::getResourceModel('test/test_collection');
            $this->setCollection($collection);
            return parent::_prepareCollection();
        }


        public function _prepareColumns()
        {
            $this->addColumn('test_id',array(
                'header' => 'Test Id',
                'align' => 'centre',
                'width' => '50px',
                'index' => 'test_id'
            ));
            $this->addColumn('first_name',array(
                'header' => 'First Name',
                'align' => 'centre',
                'width' => '50px',
                'index' => 'first_name'
            ));
            $this->addColumn('last_name',array(
                'header' => 'Last Name',
                'align' => 'centre',
                'width' => '50px',
                'index' => 'last_name'
            ));

            return parent::_prepareColumns();
        }

        public function getRowUrl($row)
        {
            return $this->getUrl('*/*/edit/', array('id' => $row->getTestId()));
        }
    }
?>