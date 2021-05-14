<?php
class Ccc_Vendor_Account_ProductController extends Mage_Core_Controller_Front_Action
{
    protected function _initProduct()
    {
        $this->_title($this->__('Catalog'))
            ->_title($this->__('Manage Products'));

        $productId = (int) $this->getRequest()->getParam('id');
        $product = Mage::getModel('vendor/product')
            ->setStoreId($this->getRequest()->getParam('store', 0));

        if (!$productId) {
            if ($setId = (int) $this->getRequest()->getParam('set')) {
                $product->setAttributeSetId($setId);
            }

            if ($typeId = $this->getRequest()->getParam('type')) {
                $product->setTypeId($typeId);
            }
        } else {
            $product->load($productId);
        }
        Mage::register('current_product', $product);
        return $product;
    }
    public function newAction()
    {
        if (!$this->_getSession()->isLoggedIn()) {
            $this->_redirect('vendor/account/login');
            return;
        }
        $this->_forward('edit');
    }
    public function indexAction()
    {
        if (!$this->_getSession()->isLoggedIn()) {
            $this->_redirect('vendor/account/login');
            return;
        }
        $this->loadLayout();
        $this->_initLayoutMessages('vendor/session');
        $this->renderLayout();
    }
    public function editAction()
    {
        if (!$this->_getSession()->isLoggedIn()) {
            $this->_redirect('vendor/account/login');
            return;
        }
        $productId = (int) $this->getRequest()->getParam('id');
        $product = $this->_initProduct();

        if ($productId && !$product->getId()) {
            $this->_getSession()->addError(Mage::helper('vendor')->__('This product no longer exists.'));
            $this->_redirect('*/*/');
            return;
        }

        $this->loadLayout();

        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

        $this->renderLayout();
    }

    public function _getSession()
    {
        return Mage::getSingleton('vendor/session');
    }
    function saveAction()
    {
        if (!$this->_getSession()->isLoggedIn()) {
            $this->_redirect('vendor/account/login');
            return;
        }
        try{
            $sku = $this->getRequest()->getPost('sku');
            $isSku = Mage::getModel('vendor/product')->getResource()->getIdBySku($sku);
            $isSkuCatalog = Mage::getModel('catalog/product')->getResource()->getIdBySku($sku);
            $data = $this->getRequest()->getPost();
            $model = Mage::getModel('vendor/product');

            $vendorProductId = $this->getRequest()->getParam('id');    
            if($vendorProductId){
                $product = Mage::getModel('vendor/product')->load($vendorProductId);
                if(!$product->getId()){
                    throw new Exception("Invalid getRequest");
                }
                $model->setEntityId($product->getId());
            }else{
                if($isSku){
                Mage::getSingleton('vendor/session')->addError(Mage::helper('vendor')->__('Sku Already exists In Vendor Product...'));
                    $this->_redirect('*/*/');
                    return;
                }
                if($isSkuCatalog){
                    Mage::getSingleton('vendor/session')->addError(Mage::helper('vendor')->__('Sku Already Exists in System Peoduct...'));
                    $this->_redirect('*/*/');
                    return;
                }
            }

            $attributeDefaultId = $model->getResource()->getEntityType()->getDefaultAttributeSetId();
            $model->addData($data);
            $model->setAttributeSetId($attributeDefaultId);
            $model->setVendorId($this->_getSession()->getVendor()->getId());
            
            $model->save();
            
            $requestModel = Mage::getModel('vendor/product_request');

            $vendorId = $this->_getSession()->getVendor()->getId();
            

            $requestModel->setVendorId($vendorId);
            $requestModel->setProductId($model->getId());
            if($vendorProductId){
                $requestModel->setRequestType('update');
            }
            else {
            $requestModel->setRequestType('insert');
            }
            $requestModel->setRequestedDate(Mage::getModel('core/date')->gmtDate('Y-m-d H:i:s'));
            
            $requestModel->save();


            Mage::getSingleton('vendor/session')->addSuccess(Mage::helper('vendor')->__('Product Saved Successfully...'));
            $this->_redirect('*/*/');
        }
        catch(Exception $e){
            Mage::getSingleton('vendor/session')->addError($e);
            $this->_redirect('*/*/');
        }
    }
    public function deleteAction()
    {
        if (!$this->_getSession()->isLoggedIn()) {
            $this->_redirect('vendor/account/login');
            return;
        }
        if ($id = $this->getRequest()->getParam('id')) {
            try 
            {
                $requestModel = Mage::getModel('vendor/product_request');

                $vendorId = $this->_getSession()->getVendor()->getId();

                $requestModel->setVendorId($vendorId);
                $requestModel->setProductId($this->getRequest()->getParam('id'));
                $requestModel->setRequestType('delete');

                $requestModel->setRequestedDate(Mage::getModel('core/date')->gmtDate('Y-m-d H:i:s'));

                $requestModel->save();

                Mage::getSingleton('vendor/session')->addSuccess(
                    Mage::helper('vendor')->__('Product Deleted....'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('vendor/session')->addError($e->getMessage());
                $this->_redirect('*/*/');
            }
        }
    }
}
