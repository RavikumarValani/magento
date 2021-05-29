<?php
    class Ccc_Order_Adminhtml_Order_CreateController extends Mage_Adminhtml_Controller_Action
    {
        protected function _getSession()
        {
            return Mage::getSingleton('order/session_quote');
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
            $this->getLayout()->getBlock('quote')->setQuote($this->getQuote());
            $this->_setActiveMenu('order/order')
                ->renderLayout();
        }

        public function updateAction()
        {
            try {
                
                $data = $this->getRequest()->getPost('order');

                if(array_filter($data['billing_address']))
                {
                    $quote = $this->getQuote();
                    $billingAddress = $quote->getBillingAddress();
                    $billingAddress->addData($data['billing_address']);
                    $billingAddress->setCustomerId($quote->getCustomerId());
                    $billingAddress->setQuoteId($quote->getId());
                    $billingAddress->setAddressType(Ccc_Order_Model_Quote_Address::TYPE_BILLING);
                    $billingAddress->save();
                }
                if(array_filter($data['shipping_address']))
                {
                    $quote = $this->getQuote();
                    $shippingAddress = $quote->getShippingAddress();
                    $shippingAddress->addData($data['shipping_address']);
                    $shippingAddress->setCustomerId($quote->getCustomerId());
                    $shippingAddress->setQuoteId($quote->getId());
                    $shippingAddress->setAddressType(Ccc_Order_Model_Quote_Address::TYPE_SHIPPING);
                    $shippingAddress->save();
                }
                $billingCheck = $this->getRequest()->getPost('billing_check');
                if($billingCheck)
                {
                    $quote = $this->getQuote();
                    $customer = $quote->getCustomer();
                    $customerBillingAddress = $customer->getBillingAddress();
                    $quoteBillingAddress = $quote->getBillingAddress()->getData();
                    unset($quoteBillingAddress['entity_id']);
                    unset($quoteBillingAddress['quote_id']);
                    unset($quoteBillingAddress['customer_address_id']);
                    unset($quoteBillingAddress['email']);
                    $customerBillingAddress->addData($quoteBillingAddress);
                    $customerBillingAddress->setParentId($quote->getCustomerId());
                    $customerBillingAddress->setIsDefaultBilling('1');
                    $customerBillingAddress->setSaveInAddressBook('1');
                    $customerBillingAddress->save();
                }
                $sameAsBilling = $this->getRequest()->getPost('same_billing');
                if($sameAsBilling)
                {
                    echo "<pre>";
                    $quote = $this->getQuote();
                    $billingAddress = $quote->getBillingAddress();
                    $address = $billingAddress->getData();
                    print_r($address);
                    unset($address['entity_id']);

                    $shippingAddress = $quote->getShippingAddress();
                    $shippingAddress->addData($address);

                    $shippingAddress->setAddressType(Ccc_Order_Model_Quote_Address::TYPE_SHIPPING);
                    print_r($shippingAddress);
                    $shippingAddress->save();
                    
                }
                $shippingCheck = $this->getRequest()->getPost('shipping_check');
                if ($shippingCheck) {
                    $quote = $this->getQuote();
                    $customer = $quote->getCustomer();
                    $customerShippingAddress = $customer->getShippingAddress();
                    $quoteShippingAddress = $quote->getShippingAddress()->getData();
                    unset($quoteShippingAddress['entity_id']);
                    unset($quoteShippingAddress['quote_id']);
                    unset($quoteShippingAddress['customer_address_id']);
                    $customerShippingAddress->addData($quoteShippingAddress);
                    $customerShippingAddress->setParentId($quote->getCustomerId());
                    $customerShippingAddress->setIsDefaultShipping('1');
                    $customerShippingAddress->setSaveInAddressBook('1');
                    $customerShippingAddress->save();
                }

                Mage::getSingleton('core/session')->addSuccess("Address has been saved.");
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

        public function accountAction()
        {
            try
            {
                $account = $this->getRequest()->getPost('account');
                $customer = $this->getQuote()->getCustomer();
                if(!$account['email'])
                {
                    throw new Exception("Enter email.");
                }
                $customer->setGroupId($account['group']);
                $customer->setEmail($account['email']);
                $customer->save();

                Mage::getSingleton('core/session')->addSuccess("Account information saved.");
                $this->_redirect('*/*/new');
            }
            catch (Exception $e){
                Mage::getSingleton('core/session')->addError($e->getMessage());
                $this->_redirect('*/*/new');
            }
            
        }

        public function methodAction()
        {
            try
            {
                $methods = $this->getRequest()->getPost('method');
                $cart = $this->getQuote();
                $shippingMethod = $methods['shipping'];
                $shippingMethod = explode(',',$shippingMethod);
                $shippingAmount = $shippingMethod[1];
                $shippingName = $shippingMethod[0];
                if(!$paymentMethod && !$shippingName)
                {
                    throw new Exception("Please select one shipping method.");
                }
                
                $cart->setShippingMethod($shippingName);
                $cart->setShippingAmount($shippingAmount);
                $cart->save();

                Mage::getSingleton('core/session')->addSuccess("Shipping method saved.");
                $this->_redirect('*/*/new');
            }
            catch (Exception $e){
                Mage::getSingleton('core/session')->addError($e->getMessage());
                $this->_redirect('*/*/new');
            }
            
        }

        public function billingMethodAction()
        {
            try
            {
                $methods = $this->getRequest()->getPost('method');
                $cart = $this->getQuote();
                $paymentMethod = $methods['payment'];
                if(!$paymentMethod)
                {
                    throw new Exception("Please select one payment method.");
                }
                $cart->setPaymentMethod($paymentMethod);
                $cart->save();

                Mage::getSingleton('core/session')->addSuccess("Payment method saved.");
                $this->_redirect('*/*/new');
            }
            catch (Exception $e){
                Mage::getSingleton('core/session')->addError($e->getMessage());
                $this->_redirect('*/*/new');
            }
            
        }

        public function saveProductAction()
        {
            try
            {
                $qtys = $this->getRequest()->getPost('product');
                if($qtys)
                {
                    foreach ($qtys as $id => $qty) {
                        $quoteItem = Mage::getModel('order/quote_item');
                        $quoteItem->load($id);
                        $quoteItem->setQuantity($qty['quantity']);
                        $quoteItem->setUpdatedAt(date("Y-m-d H:i:s"));
                        $quoteItem->save();
                    }
    
                }
                $productIds = $this->getRequest()->getParams()['massaction'];
                $quote = $this->getQuote();
                $quoteId = $quote->getId();
                $quote->addItemToCart($productIds, $quoteId);

                Mage::getSingleton('core/session')->addSuccess("Add successfully.");
                $this->_redirect('*/*/new');
            }
            catch (Exception $e){
                Mage::getSingleton('core/session')->addError($e->getMessage());
                $this->_redirect('*/*/new');
            }
        }

        public function saveAction()
        {
            try {
                $quote = $this->getQuote();
                $items = $quote->getItems();
                if(!array_filter($items))
                {
                        throw new Exception("Please add product.");
                }
                $billingAddress = $quote->getBillingAddress();
                if(!$billingAddress->getId())
                {
                    throw new Exception("Enter Billing Address.");
                }
                $shippingAddress = $quote->getShippingAddress();
                if(!$shippingAddress->getId())
                {
                    throw new Exception("Enter Shipping Address.");
                }
                if(!$quote->getPaymentMethod())
                {
                    throw new Exception("Please select payment method.");
                }
                if(!$quote->getShippingMethod())
                {
                    throw new Exception("Please select shipping method.");
                }
                $quote = $this->getQuote();
                $shippingData = $quote->getShippingAddress()->getData();
                $billingData = $quote->getBillingAddress()->getData();
                $quote->setShippingName($shippingData['firstname']);
                $quote->setBillingName($billingData['firstname']);
                $subTotal = $quote->getSubTotal();
                $quote->setBaseGrandTotal($subTotal);
                $quote->setGrandTotal($quote->getSubTotal() + $quote->getShippingAmount());
                $quote->save();
                
                $order = Mage::getModel('order/order');
                $quote = $this->getQuote();
                $data = $quote->getData();
                unset($data['entity_id']);
                $order->setData($data);
                $order->setStatus('pending');
                $order->setShippingName($shippingData['firstname']);
                $order->setBillingName($billingData['firstname']);
                $order->setCustomerId($quote->getCustomerId());
                $order->save();
                $orderId = $order->getId();
                $quote = $this->getQuote();
                $items = $quote->getItems();
                
                foreach ($items as $key => $item) {
                        $orderItem = Mage::getModel('order/order_item');
                        unset($item['item_id']);
                        unset($item['quote_id']);
                        $orderItem->setData($item);
                        $orderItem->setOrderId($orderId);
                        print_r($orderItem);
                        $orderItem->save();
                    }
                    
                    $quote = $this->getQuote();
                    $shippingAddress = $quote->getShippingAddress();
                    $shippingAddress = $shippingAddress->getData();
                    $orderAddress = Mage::getModel('order/order_address');
                    unset($shippingAddress['entity_id']);
                    $orderAddress->setData($shippingAddress);
                    $orderItem->setOrderId($orderId);
                    $orderAddress->save();
                    
                    $quote = $this->getQuote();
                    $billingAddress = $quote->getBillingAddress();
                    $billingAddress = $billingAddress->getData();
                    $orderAddress = Mage::getModel('order/order_address');
                    unset($billingAddress['entity_id']);
                    $orderAddress->setData($billingAddress);
                    $orderItem->setOrderId($orderId);
                    $orderAddress->save();
                   
                    $quote = $this->getQuote();
                    $quote->delete();
                    Mage::getSingleton('core/session')->addSuccess("Order Saved.");
                    $this->_redirect('*/adminhtml_order/index');
            }
            catch (Exception $e){
                Mage::getSingleton('core/session')->addError($e->getMessage());
                $this->_redirect('*/*/new');
            }
        }

       public function cancelAction()
       {
           $quote = $this->getQuote();
           $quote->delete();
           $this->_redirect('*/adminhtml_order/');
       }

       public function deleteAction()
       {
           try{
               $id = $this->getRequest()->getPost('delete');
               $id = key($id);
               if($id)
               {
                   $item = Mage::getModel('order/quote_item');
                   $item->load($id);
                   $item->delete();
               }
                Mage::getSingleton('core/session')->addSuccess("Item Removed.");
               $this->_redirect('*/*/new');
           }
           catch (Exception $e){
            Mage::getSingleton('core/session')->addError($e->getMessage());
            $this->_redirect('*/*/new');
            }
       }

    }
?>