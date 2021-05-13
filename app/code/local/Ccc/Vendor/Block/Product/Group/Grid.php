<?php   
class Ccc_Vendor_Block_Product_Group_Grid extends Mage_Core_Block_Template
{
    public function getAddGroupUrl()
    {
        return $this->getUrl('*/*/new');
    }

    public function getGroups()
    {
        $session = Mage::getSingleton('vendor/session');
        return Mage::getModel('vendor/product_attribute_group')->getCollection()
               ->addFieldToFilter('entity_id',array("eq" => $session->getId()));;
    }
    public function getEditUrl()
    {
        return $this->getUrl('*/*/edit');
    }
}

?>