<?php
class Ccc_Vendor_Block_Product_Edit_Form extends Mage_Core_Block_Template
{
    public function __construct()
    {
        $this->setTemplate('vendor/product/edit/form.phtml');
    }
    public function getSaveUrl()
    {
    	return $this->getUrl('*/*/save',array('id'=>$this->getRequest()->getParam('id')));
    }
    public function getBackUrl()
    {
    	return $this->getUrl('*/*');
    }
    public function getDeleteUrl()
    {
        return $this->getUrl('*/*/delete',array('id' =>$this->getRequest()->getParam('id')));
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
