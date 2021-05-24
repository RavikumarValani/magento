<?php
class Ccc_Vendor_Block_Product_Grid extends Mage_Core_Block_Template
{
    public function getAddUrl()
    {
        return $this->getUrl('*/account_product/new');
    }
    public function getDeleteUrl($id)
    {
        return $this->getUrl('*/*/delete',array('id' => $id));
    }
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }
    public function getUnApprovedUrl()
    {
        return $this->getUrl('*/account_product/unApproved');
    }
	protected function _getStore()
    {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
    }

	public function getProducts(){

		$store = $this->_getStore();	
		$collection = Mage::getModel('vendor/product')->getResourceCollection()
            ->addAttributeToSelect('sku')
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('attribute_set_id')
            ->addAttributeToSelect('type_id');

        $adminStore = Mage_Core_Model_App::ADMIN_STORE_ID;
		$collection->joinAttribute(
                'name',
                'vendor_product/name',
                'entity_id',
                null,
                'inner',
                $adminStore
            );
			$collection->joinAttribute(
                'custom_name',
                'vendor_product/name',
                'entity_id',
                null,
                'inner',
                $store->getId()
            );
            $collection->joinAttribute(
                'status',
                'vendor_product/status',
                'entity_id',
                null,
                'inner',
                $store->getId()
            );
            $collection->joinAttribute(
                'vendor_id',
                'vendor_product/vendor_id',
                'entity_id',
                null,
                'inner',
                $store->getId()
            );
            $collection->joinAttribute(
                'price',
                'vendor_product/price',
                'entity_id',
                null,
                'left',
                $store->getId()
            );

             $collection->getSelect()->join(
             array(
                'vendor_product_request'=> 'vendor_product_request'),
                'vendor_product_request.product_id = e.entity_id',
             array('*'));
            
            $collection->addFieldToFilter('vendor_id',array('eq'=>$this->_getSession()->getVendor()->getId()));
            
            $collection->getSelect()->where('requested_date IN (SELECT MAX(requested_date) FROM vendor_product_request GROUP BY product_id)');

        return $collection;
	}
	public function getEditUrl($id = null)
    {
        return $this->getUrl('*/*/edit', array('id' => $id));
    }
    public function _getSession()
    {
        return Mage::getSingleton('vendor/session');
    }
}
