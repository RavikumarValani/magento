<?php

class Ccc_Vendor_Block_Product_Group_Edit extends Mage_Core_Block_Template
{
    public function getSaveUrl()
    {
        return $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id')));
    }
    protected function _getSession()
    {
        return Mage::getSingleton('vendor/session');
    }
    public function getBackUrl()
    {
        return $this->getUrl('*/*/index');
    }
    public function getGroupData()
    {
        return Mage::registry('current_group');
    }
    public function getDeleteUrl()
    {
        return $this->getUrl('*/*/delete',array('id'=>Mage::registry('current_group')['attribute_group_id']));
    }
    public function isDeleteVisible()
    {
        if($this->getRequest()->getParam('id'))
        {
            return true;
        }
        return false;
    }
}
