<?php

class Ccc_Vendor_Adminhtml_ProductController extends Mage_Adminhtml_Controller_Action
{

    // protected function _isAllowed()
    // {
    //     return Mage::getSingleton('admin/session')->isAllowed('vendor/product');
    // }

    public function indexAction()
    {
        $this->loadLayout();
        $this->_setActiveMenu('vendor');
        $this->_title('Product Grid');

        $this->_addContent($this->getLayout()->createBlock('vendor/adminhtml_product'));

        $this->renderLayout();
    }

    // protected function _initVendor()
    // {
    //     $this->_title($this->__('Vendor'))
    //         ->_title($this->__('Manage vendors'));

    //     $vendorId = (int) $this->getRequest()->getParam('id');
    //     $vendor   = Mage::getModel('vendor/product')
    //         ->setStoreId($this->getRequest()->getParam('store', 0))
    //         ->load($vendorId);

    //     Mage::register('vendor_product', $vendor);
    //     Mage::getSingleton('cms/wysiwyg_config')->setStoreId($this->getRequest()->getParam('store'));
    //     return $vendor;
    // }

    public function approvedAction()
    {
        $productId = Mage::getModel('vendor/product_request')->load($this->getRequest()->getParam('id'))->getProductId();
        $vendorId = Mage::getModel('vendor/product_request')->load($this->getRequest()->getParam('id'))->getVendorId();

        $productData = Mage::getModel('vendor/product')->load($productId)->getData();

        $catalogProduct = Mage::getModel('catalog/product');
        $attributeSetId = $catalogProduct->getResource()->getEntityType()->getDefaultAttributeSetId();
        $entityTypeId = $catalogProduct->getResource()->getEntityType()->getEntityTypeId();

        $catalogProduct->addData($productData);
        $catalogProduct->setAttributeSetId($attributeSetId);
        $catalogProduct->setEntityTypeId($entityTypeId);
        $catalogProduct->setVendorId($vendorId);

        $catalogProduct->save();

        
        $productRequest = Mage::getModel('vendor/product_request');
        $productRequest->setRequestId($this->getRequest()->getParam('id'));
        $productRequest->setCatalogProductId($catalogProduct->getId());
        $productRequest->setRequest('1');
        $productRequest->setApproved(1);

        $productRequest->setRequestApprovedDate(Mage::getModel('core/date')->gmtDate('Y-m-d H:i:s'));
        $productRequest->save();

        $productRequest->load($productRequest->getRequestId());
        
        if($productRequest->getRequestType() == 'delete')
        {
            $this->_forward('vendorDelete');
        }

       Mage::getSingleton('core/session')->addSuccess($this->__('The Product Approved Successfully...'));
       $this->_redirect('*/*/');
        
        
    }

    public function unApprovedAction()
    {
        $productRequest = Mage::getModel('vendor/product_request');
        $productRequest->setRequestId($this->getRequest()->getParam('id'));
        $productRequest->setRequest('1');
        $productRequest->setApproved(0);

        $productRequest->setRequestApprovedDate(Mage::getModel('core/date')->gmtDate('Y-m-d H:i:s'));
        $productRequest->save();

        Mage::getSingleton('core/session')->addSuccess($this->__('The Product UnApproved Successfully...'));
       $this->_redirect('*/*/');
    }

    public function vendorDeleteAction()
    {
        $productRequest = Mage::getModel('vendor/product_request');
        $product = Mage::getModel('vendor/product');

        echo $requestId = $this->getRequest()->getParam('id');

        $productRequest->load($requestId);
        $product->load($productRequest->getProductId());
    
        $product->delete();
        
    }

    // public function newAction()
    // {
    //     $this->_forward('edit');
    // }

    // public function editAction()
    // {
    //     $vendorId = (int) $this->getRequest()->getParam('id');
    //     $vendor   = $this->_initVendor();

    //     if ($vendorId && !$vendor->getId()) {
    //         $this->_getSession()->addError(Mage::helper('vendor')->__('This vendor no longer exists.'));
    //         $this->_redirect('*/*/');
    //         return;
    //     }

    //     $this->_title($vendor->getName());

    //     $this->loadLayout();

    //     $this->_setActiveMenu('vendor/product');

    //     $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

    //     $this->renderLayout();

    // }

    // public function saveAction()
    // {
    //     $storeId        = $this->getRequest()->getParam('store');
    //     $redirectBack   = $this->getRequest()->getParam('back', false);
    //     $productId      = $this->getRequest()->getParam('id');
    //     $isEdit         = (int)($this->getRequest()->getParam('id') != null);

    //     $data = $this->getRequest()->getPost();
        
    //     if ($data) {

    //         $product = $this->_initProductSave();

    //         try {
    //             $product->save();
    //             $productId = $product->getId();

    //             if (isset($data['copy_to_stores'])) {
    //                $this->_copyAttributesBetweenStores($data['copy_to_stores'], $product);
    //             }

    //             $this->_getSession()->addSuccess($this->__('The product has been saved.'));
    //         } catch (Mage_Core_Exception $e) {
    //             $this->_getSession()->addError($e->getMessage())
    //                 ->setProductData($data);
    //             $redirectBack = true;
    //         } catch (Exception $e) {
    //             Mage::logException($e);
    //             $this->_getSession()->addError($e->getMessage());
    //             $redirectBack = true;
    //         }
    //     }

    //     if ($redirectBack) {
    //         $this->_redirect('*/*/edit', array(
    //             'id'    => $productId,
    //             '_current'=>true
    //         ));
    //     } elseif($this->getRequest()->getParam('popup')) {
    //         $this->_redirect('*/*/created', array(
    //             '_current'   => true,
    //             'id'         => $productId,
    //             'edit'       => $isEdit
    //         ));
    //     } else {
    //         $this->_redirect('*/*/', array('store'=>$storeId));
    //     }
    // }
    // const MAX_QTY_VALUE = 99999999.9999;
    // protected function _filterStockData(&$stockData)
    // {
    //     if (is_null($stockData)) {
    //         return;
    //     }
    //     if (!isset($stockData['use_config_manage_stock'])) {
    //         $stockData['use_config_manage_stock'] = 0;
    //     }
    //     if (isset($stockData['qty']) && (float)$stockData['qty'] > self::MAX_QTY_VALUE) {
    //         $stockData['qty'] = self::MAX_QTY_VALUE;
    //     }
    //     if (isset($stockData['min_qty']) && (int)$stockData['min_qty'] < 0) {
    //         $stockData['min_qty'] = 0;
    //     }
    //     if (!isset($stockData['is_decimal_divided']) || $stockData['is_qty_decimal'] == 0) {
    //         $stockData['is_decimal_divided'] = 0;
    //     }
    // }

    // protected function _copyAttributesBetweenStores(array $stores, Ccc_Vendor_Model_Product $product)
    // {
    //     foreach ($stores as $storeTo => $storeFrom) {
    //         $productInStore = Mage::getModel('vendor/product')
    //             ->setStoreId($storeFrom)
    //             ->load($product->getId());
    //         Mage::dispatchEvent('product_duplicate_attributes', array(
    //             'product' => $productInStore,
    //             'storeTo' => $storeTo,
    //             'storeFrom' => $storeFrom,
    //         ));
    //         $productInStore->setStoreId($storeTo)->save();
    //     }
    //     return $this;
    // }

    // protected function _initProductSave()
    // {
    //     $product     = $this->_initProduct();
    //     $productData = $this->getRequest()->getPost('product');
        
        
    //     $product->addData($productData);

    //     if (Mage::app()->isSingleStoreMode()) {
    //         $product->setWebsiteIds(array(Mage::app()->getStore(true)->getWebsite()->getId()));
    //     }

    //     /**
    //      * Create Permanent Redirect for old URL key
    //      */
    //     if ($product->getId() && isset($productData['url_key_create_redirect']))
    //     // && $product->getOrigData('url_key') != $product->getData('url_key')
    //     {
    //         $product->setData('save_rewrites_history', (bool)$productData['url_key_create_redirect']);
    //     }

    //     /**
    //      * Check "Use Default Value" checkboxes values
    //      */
    //     if ($useDefaults = $this->getRequest()->getPost('use_default')) {
    //         foreach ($useDefaults as $attributeCode) {
    //             $product->setData($attributeCode, false);
    //         }
    //     }

        /**
         * Init product links data (related, upsell, crosssel)
         */
        // $links = $this->getRequest()->getPost('links');
        // if (isset($links['related']) && !$product->getRelatedReadonly()) {
        //     $product->setRelatedLinkData(Mage::helper('adminhtml/js')->decodeGridSerializedInput($links['related']));
        // }
        // if (isset($links['upsell']) && !$product->getUpsellReadonly()) {
        //     $product->setUpSellLinkData(Mage::helper('adminhtml/js')->decodeGridSerializedInput($links['upsell']));
        // }
        // if (isset($links['crosssell']) && !$product->getCrosssellReadonly()) {
        //     $product->setCrossSellLinkData(Mage::helper('adminhtml/js')
        //         ->decodeGridSerializedInput($links['crosssell']));
        // }
        // if (isset($links['grouped']) && !$product->getGroupedReadonly()) {
        //     $product->setGroupedLinkData(Mage::helper('adminhtml/js')->decodeGridSerializedInput($links['grouped']));
        // }

        /**
         * Initialize product categories
         */
        // $categoryIds = $this->getRequest()->getPost('category_ids');
        // if (null !== $categoryIds) {
        //     if (empty($categoryIds)) {
        //         $categoryIds = array();
        //     }
        //     $product->setCategoryIds($categoryIds);
        // }

        /**
         * Initialize data for configurable product
         */
        // if (($data = $this->getRequest()->getPost('configurable_products_data'))
        //     && !$product->getConfigurableReadonly()
        // ) {
        //     $product->setConfigurableProductsData(Mage::helper('core')->jsonDecode($data));
        // }
        // if (($data = $this->getRequest()->getPost('configurable_attributes_data'))
        //     && !$product->getConfigurableReadonly()
        // ) {
        //     $product->setConfigurableAttributesData(Mage::helper('core')->jsonDecode($data));
        // }

        // $product->setCanSaveConfigurableAttributes(
        //     (bool) $this->getRequest()->getPost('affect_configurable_product_attributes')
        //         && !$product->getConfigurableReadonly()
        // );

        /**
         * Initialize product options
         */
    //     if (isset($productData['options']) && !$product->getOptionsReadonly()) {
    //         $product->setProductOptions($productData['options']);
    //     }

    //     $product->setCanSaveCustomOptions(
    //         (bool)$this->getRequest()->getPost('affect_product_custom_options')
    //         && !$product->getOptionsReadonly()
    //     );

    //     Mage::dispatchEvent(
    //         'catalog_product_prepare_save',
    //         array('product' => $product, 'request' => $this->getRequest())
    //     );

    //     return $product;
    // }

    // protected function _initProduct()
    // {
    //     $this->_title($this->__('Catalog'))
    //          ->_title($this->__('Manage Products'));

    //     $productId  = (int) $this->getRequest()->getParam('id');
    //     $product    = Mage::getModel('vendor/product')
    //         ->setStoreId($this->getRequest()->getParam('store', 0));
    //     $product->setAttributeSetId(1);
    //     if (!$productId) {
    //         if ($setId = (int) $this->getRequest()->getParam('set')) {
    //             $product->setAttributeSetId($setId);
    //         }

    //         if ($typeId = $this->getRequest()->getParam('type')) {
    //             $product->setTypeId($typeId);
    //         }
    //     }

    //     $product->setData('_edit_mode', true);
    //     if ($productId) {
    //         try {
    //             $product->load($productId);
    //         } catch (Exception $e) {
    //             $product->setTypeId(Mage_Catalog_Model_Product_Type::DEFAULT_TYPE);
    //             Mage::logException($e);
    //         }
    //     }

    //     $attributes = $this->getRequest()->getParam('attributes');
    //     if ($attributes && $product->isConfigurable() &&
    //         (!$productId || !$product->getTypeInstance()->getUsedProductAttributeIds())) {
    //         $product->getTypeInstance()->setUsedProductAttributeIds(
    //             explode(",", base64_decode(urldecode($attributes)))
    //         );
    //     }

    //     // Required attributes of simple product for configurable creation
    //     if ($this->getRequest()->getParam('popup')
    //         && $requiredAttributes = $this->getRequest()->getParam('required')) {
    //         $requiredAttributes = explode(",", $requiredAttributes);
    //         foreach ($product->getAttributes() as $attribute) {
    //             if (in_array($attribute->getId(), $requiredAttributes)) {
    //                 $attribute->setIsRequired(1);
    //             }
    //         }
    //     }

    //     if ($this->getRequest()->getParam('popup')
    //         && $this->getRequest()->getParam('product')
    //         && !is_array($this->getRequest()->getParam('product'))
    //         && $this->getRequest()->getParam('id', false) === false) {

    //         $configProduct = Mage::getModel('catalog/product')
    //             ->setStoreId(0)
    //             ->load($this->getRequest()->getParam('product'))
    //             ->setTypeId($this->getRequest()->getParam('type'));

    //         /* @var $configProduct Mage_Catalog_Model_Product */
    //         $data = array();
    //         foreach ($configProduct->getTypeInstance()->getEditableAttributes() as $attribute) {

    //             /* @var $attribute Mage_Catalog_Model_Resource_Eav_Attribute */
    //             if(!$attribute->getIsUnique()
    //                 && $attribute->getFrontend()->getInputType()!='gallery'
    //                 && $attribute->getAttributeCode() != 'required_options'
    //                 && $attribute->getAttributeCode() != 'has_options'
    //                 && $attribute->getAttributeCode() != $configProduct->getIdFieldName()) {
    //                 $data[$attribute->getAttributeCode()] = $configProduct->getData($attribute->getAttributeCode());
    //             }
    //         }

    //         $product->addData($data);
    //             // ->setWebsiteIds($configProduct->getWebsiteIds());
    //     }

    //     // Mage::register('vendor_product', $product);
    //     Mage::register('vendor_product', $product);
    //     // Mage::getSingleton('cms/wysiwyg_config')->setStoreId($this->getRequest()->getFsaParam('store'));
    //     return $product;
    // }


    // public function deleteAction()
    // {
    //     try {

    //         $vendorModel = Mage::getModel('vendor/product');

    //         if (!($vendorId = (int) $this->getRequest()->getParam('id')))
    //             throw new Exception('Id not found');

    //         if (!$vendorModel->load($vendorId)) {
    //             throw new Exception('vendor does not exist');
    //         }

    //         if (!$vendorModel->delete()) {
    //             throw new Exception('Error in delete record', 1);
    //         }

    //         Mage::getSingleton('core/session')->addSuccess($this->__('The vendor has been deleted.'));

    //     } catch (Exception $e) {
    //         Mage::logException($e);
    //         $Mage::getSingleton('core/session')->addError($e->getMessage());
    //     }
        
    //     $this->_redirect('*/*/');
    // }
}
