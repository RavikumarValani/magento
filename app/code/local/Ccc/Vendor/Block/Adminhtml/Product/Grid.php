<?php

class Ccc_Vendor_Block_Adminhtml_Product_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('vendorProductGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(false);
        $this->setVarNameFilter('vendor_filter');
    }

    protected function _getStore()
    {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('vendor/product_request')->getResourceCollection();
        $collection->getSelect()->join(
            array(
                'vendor_product' => 'vendor_product_entities'),
            'vendor_product.entity_id = main_table.product_id',
            array('*')
            );
        $this->setCollection($collection);
        $collection->getSelect()->where('request != 1');
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('entity_id',
            array(
                'header'=> Mage::helper('vendor')->__('ID'),
                'width' => '50px',
                'type'  => 'number',
                'index' => 'entity_id',
        ));


        $this->addColumn('type',
            array(
                'header'=> Mage::helper('vendor')->__('Type'),
                'width' => '60px',
                'index' => 'type_id',
                'type'  => 'options',
                'options' => Mage::getSingleton('vendor/product_type')->getOptionArray(),
        ));

        $this->addColumn('sku',
            array(
                'header'=> Mage::helper('vendor')->__('SKU'),
                'width' => '80px',
                'index' => 'sku',
        ));

        $this->addColumn('vendor_id',
            array(
                'header'=> Mage::helper('vendor')->__('vendorId'),
                'index' => 'vendor_id',
        ));

        $this->addColumn('request_type',
            array(
                'header'=> Mage::helper('vendor')->__('RequestType'),
                'width' => '70px',
                'index' => 'request_type'
        ));


        $this->addColumn('approved',
            array(
                'header' => Mage::helper('catalog')->__('Approved'),
                
                'type' => 'action',
                'getter' => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('catalog')->__('Approved'),
                        'url' => array(
                            'base' => '*/*/approved',
                            'params' => array('store' => $this->getRequest()->getParam('store')),
                        ),
                        'field' => 'id',
                    ),
                ),
                'filter' => false,
                'sortable' => false,
                'index' => 'stores',
            ));
        $this->addColumn('unapproved',
            array(
                'header' => Mage::helper('catalog')->__('UnApproved'),
                
                'type' => 'action',
                'getter' => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('catalog')->__('UnApproved'),
                        'url' => array(
                            'base' => '*/*/unApproved',
                            'params' => array('store' => $this->getRequest()->getParam('store')),
                        ),
                        'field' => 'id',
                    ),
                ),
                'filter' => false,
                'sortable' => false,
                'index' => 'stores',
            ));

        

        return parent::_prepareColumns();
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/index', array('_current' => true));
    }

}
