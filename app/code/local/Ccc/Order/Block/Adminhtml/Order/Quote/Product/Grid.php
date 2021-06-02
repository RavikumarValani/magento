<?php
class Ccc_Order_Block_Adminhtml_Order_Quote_Product_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('order_create_search_grid');
        $this->setDefaultSort('entity_id');
        $this->setUseAjax(true);
    }

    /**
     * Retrieve quote store object
     * @return Mage_Core_Model_Store
     */
    public function getStore()
    {
        return Mage::getSingleton('order/session_quote')->getStore();
    }

    
    protected function _prepareCollection()
    {
        $attributes = Mage::getSingleton('catalog/config')->getProductAttributes();
        /* @var $collection Mage_Catalog_Model_Resource_Product_Collection */
        $collection = Mage::getModel('catalog/product')->getCollection();
        $collection
            ->setStore($this->getStore())
            ->addAttributeToSelect($attributes)
            ->addAttributeToSelect('sku')
            ->addStoreFilter()
            ->addAttributeToFilter('type_id', array_keys(
                Mage::getConfig()->getNode('adminhtml/sales/order/create/available_product_types')->asArray()
            ))
            ->addAttributeToSelect('gift_message_available');

        Mage::getSingleton('catalog/product_status')->addSaleableFilterToCollection($collection);

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * Prepare columns
     *
     * @return Mage_Adminhtml_Block_Sales_Order_Create_Search_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn('entity_id', array(
            'header'    => Mage::helper('order')->__('ID'),
            'sortable'  => true,
            'width'     => '60',
            'index'     => 'entity_id',
        ));
        $this->addColumn('name', array(
            'header'    => Mage::helper('order')->__('Product Name'),
            'index'     => 'name',
        ));
        $this->addColumn('sku', array(
            'header'    => Mage::helper('order')->__('SKU'),
            'width'     => '80',
            'index'     => 'sku',
            'disabled' => true,
        ));
        $this->addColumn('price', array(
            'header'    => Mage::helper('order')->__('Price'),
            'column_css_class' => 'price',
            'align'     => 'center',
            'index'     => 'price',
        ));

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');

        $this->getMassactionBlock()->addItem('addCart', array(
             'label'=> Mage::helper('order')->__('Add to cart'),
             'url'  => $this->getUrl('*/*/saveProduct')
        ));

        return $this;
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid');
    }
}
