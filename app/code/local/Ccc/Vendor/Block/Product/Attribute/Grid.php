<?php
class Ccc_Vendor_Block_Product_Attribute_Grid extends Mage_Core_Block_Template
{
    protected function getAttributes()
    {
        $vendorId = $this->_getSession()->getVendor()->getId();

        $collection = Mage::getModel('vendor/resource_product_attribute_collection')->addFieldToFilter('attribute_code', array('like' => '%' . $vendorId . '%'))->getData();
        return $collection;
    }

    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    public function getAddUrl()
    {
        return $this->getUrl('*/*/new');
    }

    protected function _getSession()
    {
        return Mage::getSingleton('vendor/session');
    }

    public function getVendor()
    {
        return $this->_getSession()->getVendor();
    }

    public function getEditUrl()
    {
        return $this->getUrl('*/*/edit');
    }
}
