<?php

class Ccc_Compile_Block_Adminhtml_Compile_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('compileGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
        $this->setVarNameFilter('compile_filter');

    }

    protected function _getStore()
    {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
    }

    protected function _prepareCollection()
    {
        $store = $this->_getStore();

        $collection = Mage::getModel('compile/compile')->getCollection()
            ->addAttributeToSelect('firstname')
            ->addAttributeToSelect('lastname')
            ->addAttributeToSelect('email')
            ->addAttributeToSelect('phoneno');
        
        $collection->joinAttribute(
            'firstname',
            'compile/firstname',
            'entity_id',
            null,
            'left'
        );

        $collection->joinAttribute(
            'lastname',
            'compile/lastname',
            'entity_id',
            null,
            'left'
        );
        $collection->joinAttribute(
            'email',
            'compile/email',
            'entity_id',
            null,
            'left'
        );
        $collection->joinAttribute(
            'phoneno',
            'compile/phoneno',
            'entity_id',
            null,
            'left'
        );

        $collection->joinAttribute(
            'id',
            'compile/entity_id',
            'entity_id',
            null,
            'left'
        );
        $this->setCollection($collection);
        parent::_prepareCollection();
        return $this;
    }

    protected function _prepareColumns()
    {
        $this->addColumn('id',
            array(
                'header' => Mage::helper('compile')->__('id'),
                'width'  => '50px',
                'index'  => 'id',
            ));
        $this->addColumn('firstname',
            array(
                'header' => Mage::helper('compile')->__('First Name'),
                'width'  => '50px',
                'index'  => 'firstname',
            ));

        $this->addColumn('lastname',
            array(
                'header' => Mage::helper('compile')->__('Last Name'),
                'width'  => '50px',
                'index'  => 'lastname',
            ));

        $this->addColumn('email',
            array(
                'header' => Mage::helper('compile')->__('Email'),
                'width'  => '50px',
                'index'  => 'email',
            ));

        $this->addColumn('phoneNo',
            array(
                'header' => Mage::helper('compile')->__('Phone Number'),
                'width'  => '50px',
                'index'  => 'phoneno',
            ));

        $this->addColumn('action',
            array(
                'header'   => Mage::helper('compile')->__('Action'),
                'width'    => '50px',
                'type'     => 'action',
                'getter'   => 'getId',
                'actions'  => array(
                    array(
                        'caption' => Mage::helper('compile')->__('Delete'),
                        'url'     => array(
                            'base' => '*/*/delete',
                        ),
                        'field'   => 'id',
                    ),
                ),
                'filter'   => false,
                'sortable' => false,
            ));

        parent::_prepareColumns();
        return $this;
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current' => true));
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array(
            'store' => $this->getRequest()->getParam('store'),
            'id'    => $row->getId())
        );
    }
}
