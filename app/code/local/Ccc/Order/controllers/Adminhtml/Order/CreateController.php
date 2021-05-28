<?php
    class Ccc_Order_Adminhtml_Order_CreateController extends Mage_Adminhtml_Controller_Action
    {
        protected function _getSession()
        {
            return Mage::getSingleton('order/session_quote');
        }

        protected function _getOrderCreateModel()
        {
            return Mage::getSingleton('order/order_create');
        }
        public function indexAction()
        {
            $this->_title($this->__('Orders'))->_title($this->__('New Order'));
            $this->loadLayout();

            $this->_setActiveMenu('order/order')
                ->renderLayout();
        }

        public function startAction()
        {
            $this->_getSession()->clear();
            $this->_getSession()->setCustomerId((int)$this->getRequest()->getParam('customer_id'));
            $this->getQuote();
            $this->_redirect('*/*/new');
        }

        public function newAction()
        {
            $this->_title($this->__('Orders'))->_title($this->__('New Order'));
            $this->loadLayout();
            // $this->_addContent($this->getLayout()->createBlock('order/adminhtml_order_create_items'));
            $this->_setActiveMenu('order/order')
                ->renderLayout();
        }

        public function updateAction()
        {
            try {
                $billingCheck = $this->getRequest()->getPost('billing_check');
            if($billingCheck)
            {
                $customer = Mage::getModel('customer/customer')->load($this->_getSession()->getCustomerId());
                $customerAddress = $customer->getDefaultBillingAddress();
                $data = $this->getRequest()->getPost('order');
                $customerAddress->addData($data['billing_address']);
                $customerAddress->save();
            }
            $sameBilling = $this->getRequest()->getPost('same_billing');
            if($sameBilling)
            {
                $quoteId = $this->getQuote()->getId();
                $customerId = $this->_getSession()->getCustomerId();
                $address = Mage::getModel('order/quote_address');
                $collection = $address->getCollection();
                $collection->getSelect()->reset(Zend_Db_Select::COLUMNS)->where(new Zend_Db_Expr("quote_id = {$quoteId} AND customer_id = {$customerId} AND address_type='shipping'"))->columns('entity_id');
                $addressId = $collection->getColumnValues('entity_id')[0];
                $billingCollection = $address->getCollection();
                $billingCollection->getSelect()->reset(Zend_Db_Select::COLUMNS)->where(new Zend_Db_Expr("quote_id = {$quoteId} AND customer_id = {$customerId} AND address_type='billing'"))->columns('*');
                $billing = $billingCollection->getResource()->getReadConnection()->fetchRow($billingCollection->getSelect());
                unset($billing['entity_id']);
                $address->setCustomerId($billing['customer_id']);
                $address->setFirstname($billing['firstname']);
                $address->setQuoteId($billing['quote_id']);
                $address->setLastname($billing['customer_id']);
                $address->setMiddlename($billing['customer_id']);
                $address->setPrefix($billing['customer_id']);
                $address->setSuffix($billing['customer_id']);
                $address->setMiddlename($billing['customer_id']);
                $address->setCompany($billing['customer_id']);
                $address->setCity($billing['customer_id']);
                $address->setStreet($billing['customer_id']);
                $address->setAddressType('shipping');
                $address->save();
            }
            $shippingCheck = $this->getRequest()->getPost('shipping_check');
            if($shippingCheck)
            {
                $customer = Mage::getModel('customer/customer')->load($this->_getSession()->getCustomerId());
                $customerAddress = $customer->getDefaultShippingingAddress();
                $data = $this->getRequest()->getPost('order');
                if(!$customerAddress)
                {
                    $customerAddress = Mage::getModel('customer/address');
                    $customerAddress->setData($data['shipping_address']);
                    $customerAddress->ParentId = $this->_getSession()->getCustomerId();
                    $customerAddress->save();
                }
                else{
                    $customerAddress->addData($data['shipping_address']);
                    $customerAddress->save();
                }
            }
            $data = $this->getRequest()->getPost('order');
            $quoteAddress = Mage::getModel('order/quote_address');
            $quoteId = $quoteAddress->getQuoteId();
            $customerId = $this->_getSession()->getCustomerId();
            if($data['billing_address'])
            {
                $collection = $quoteAddress->getCollection();
                $collection->getSelect()->reset(Zend_Db_Select::COLUMNS)->where(new Zend_Db_Expr("quote_id = {$quoteId} AND customer_id = {$customerId} AND address_type='billing'"))->columns('entity_id');
                $addressId = $collection->getColumnValues('entity_id')[0];
                if($addressId)
                {
                    $quoteAddress->load($addressId);
                    $quoteAddress->addData($data['billing_address']);
                }
                else
                {
                    $quoteAddress->setData($data['billing_address']);
                    $quoteAddress->CustomerId = $this->_getSession()->getCustomerId();
                    $quoteAddress->QuoteId = $quoteId;
                    $quoteAddress->addressType = 'billing';
                }
                $quoteAddress->save();
                
            }
            if($data['shipping_address'])
            {
                $collection = $quoteAddress->getCollection();
                $collection->getSelect()->reset(Zend_Db_Select::COLUMNS)->where(new Zend_Db_Expr("quote_id = {$quoteId} AND customer_id = {$customerId} AND address_type='shipping'"))->columns('entity_id');
                $addressId = $collection->getColumnValues('entity_id')[0];
                if($addressId)
                {
                    $quoteAddress->load($addressId);
                    $quoteAddress->addData($data['shipping_address']);

                }
                else
                {
                    $quoteAddress->setData($data['shipping_address']);
                    $quoteAddress->CustomerId = $this->_getSession()->getCustomerId();
                    $quoteAddress->QuoteId = $quoteId;
                    $quoteAddress->addressType = 'shipping';
                }
                $quoteAddress->save();
            }
            $this->_redirect('*/*/new');

            }
            catch (Exception $e){
                Mage::getSingleton('core/session')->addError($e->getMessage());
                $this->_redirect('*/*/new');
            }
            

        }

        protected function getQuote()
        {
            try{
                $quote = Mage::getModel('order/quote');
                $customerId = $this->_getSession()->getCustomerId();  
                if(!$customerId)
                {
                    throw new Exception('Customer id not found.');
                }
                $customer = Mage::getModel('customer/customer')->load($customerId);
                if(!$customer->getId())
                {
                    throw new Exception('Customer not found.');
                }
                $quote = Mage::getModel('order/quote');
                $quote->setCustomer($customer);
                $quote->load($customerId, 'customer_id');
                if($quote->getId())
                {
                    return $quote;
                }
                $quote->setCustomerId((int)$this->getRequest()->getParam('customer_id'));
                $quote->createdAt = date("Y-m-d H:i:s");
                $quote->save();
                return $quote;
            }catch(Exception $e){
                Mage::getSingleton('core/session')->addError($e->getMessage());
                $this->_redirect('*/*/');
            }
            
            
        }

        public function saveProductAction()
        {
            $qtys = $this->getRequest()->getPost('product');
            if($qtys)
            {
                foreach ($qtys as $id => $qty) {
                    $quoteItem = Mage::getModel('order/quote_item');
                    $quoteItem->load($id);
                    $quoteItem->Quantity = $qty['quantity'];
                    $quoteItem->UpdatedAt = date("Y-m-d H:i:s");
                    $quoteItem->save();
                }

            }
            $productIds = $this->getRequest()->getParams()['massaction'];
            $quote = Mage::getModel('order/quote');
            $quoteItem = Mage::getModel('order/quote_item');
            $quoteId = $quoteItem->getQuoteId();
            $quote->addItemToCart($productIds, $quoteId);
            $this->_redirect('*/*/new');
        }

        public function saveAction()
        {
            try {
                $shippingMethod = $this->getRequest()->getPost('shipingMethod');
                if(!$shippingMethod)
                {
                        throw new Exception("Please select shippingmethod.");
                }
                $billingMethod = $this->getRequest()->getPost('billingMethod');
                if(!$billingMethod)
                {
                        throw new Exception("Please select billingmethod.");
                }
                $quote = $this->getQuote();
                $quote->setShippingName($quote->getShippingAdddress()['firstname']);
                $quote->setBillingName($quote->getBillingAdddress()['firstname']);
                $quote->setShippingMethod($shippingMethod);
                $quote->setPaymentMethod($billingMethod);
                $quote->setBaseGrandTotal($this->getSubTotal());
                $quote->setGrandTotal($this->getSubTotal());
                $quote->save();
                $order = Mage::getModel('order/order');
                $quote = $this->getQuote();
                $data = $quote->getData();
                unset($data['entity_id']);
                $order->setData($data);
                $order->setStatus(1);
                $order->setCustomerId($this->_getSession()->getCustomerId());
                $order->save();
                $quote = Mage::getModel('order/quote');
                $items = $quote->getItems();
                if(!$items)
                {
                        throw new Exception("Please add product.");
                }
                    foreach ($items as $key => $item) {
                            $orderItem = Mage::getModel('order/order_item');
                            unset($item['item_id']);
                            unset($item['quote_id']);
                            $orderItem->setData($item);
                            print_r($orderItem);
                            // $orderItem->save();
                        }
                        
                        
                        $quote = Mage::getModel('order/quote');
                        $shippingAddress = $quote->getShippingAdddress();
                        if(!$shippingAddress)
                        {
                            throw new Exception("Enter Shipping Address.");
                        }
                        $orderAddress = Mage::getModel('order/order_address');
                        unset($shippingAddress['entity_id']);
                        $orderAddress->setData($shippingAddress);
                        $orderAddress->save();
                        $quote = Mage::getModel('order/quote');
                        $billingAddress = $quote->getBillingAdddress();
                        if(!$billingAddress)
                        {
                            throw new Exception("Enter Billing Address.");
                        }
                        $orderAddress = Mage::getModel('order/order_address');
                        unset($billingAddress['entity_id']);
                        $orderAddress->setData($billingAddress);
                        $orderAddress->save();
                        $quote = $this->getQuote();
                        $quote->delete();
                        $this->_redirect('*/adminhtml_order/index');
            }
            catch (Exception $e){
                Mage::getSingleton('core/session')->addError($e->getMessage());
                $this->_redirect('*/*/new');
            }
        }

        protected function _getQuote()
        {
            return $this->_getSession()->getQuote();
        }

        protected function _reloadQuote()
        {
            $id = $this->_getQuote()->getId();
            $this->_getQuote()->load($id);
            return $this;
        }

        public function getSubTotal()
        {
            $sub = 0;
            $quote = Mage::getModel('order/quote');
            $items = $quote->getItems();
            foreach($items as $item)
            {
                $sub = $sub + $item['price'];
            }
            return $sub;
        }
    }
?>