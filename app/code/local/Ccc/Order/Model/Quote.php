<?php
class Ccc_Order_Model_Quote extends Mage_Core_Model_Abstract
{
    protected $customer = null;
    protected $items = [];
    protected $billingAddress = null;
    protected $shippingAddress = null;
    protected $shippingMethodId = null;

    protected function _construct()
    {
        $this->_init('order/quote');
    }
    
    public function addItemToCart($products,$quoteId)
    {
        foreach ($products as $key => $id) {
            $item = Mage::getModel('order/quote_item');
            $product = Mage::getModel('catalog/product');
            $product->load($id);
            $productId = $product->getEntityId();
            $collection = $item->getCollection();
            $collection->getSelect()->reset(Zend_Db_Select::COLUMNS)->where(new Zend_Db_Expr("quote_id = {$quoteId} AND product_Id = {$id}"))->columns('item_id');
            $itemId = $collection->getResource()->getReadConnection()->fetchOne($collection->getSelect());
            $item->load($itemId);
            if($item->getData())
            {
                $item->Quantity = $item->getQuantity() + 1;
                $item->UpdatedAt = date("Y-m-d H:i:s");
                $item->save();
            }
            else
            {
                $item = Mage::getModel('order/quote_item');
                $item->QuoteId = $quoteId;
                $item->Quantity = 1;
                $item->ProductId = $product->getEntityId();
                $item->Sku = $product->getSku();
                $item->Price = $product->getPrice();
                $item->Name = $product->getName();
                $item->CreatedAt = date("Y-m-d H:i:s");
                $item->save();
            }
        }
        return;
    }

    protected function _getSession()
    {
        return Mage::getSingleton('order/session_quote');
    }

    public function setCustomer(Mage_Customer_Model_Customer $customer)
    {
        $this->customer = $customer;
        return $this;
    }
    public function getCustomer()
    {
        if($this->customer)
        {
            return $this->customer;
        }
        if(!$this->customerId)
        {
            return false;
        }
        $customer = Mage::getModel('Customer/Customer')->load($this->customerId);
        $this->setCustomer($customer);
        return $this->customer;
    }
    public function getItems()
    {
        $item = Mage::getModel('order/quote_item');
        $collection = $item->getCollection();
        $customerId = $this->_getSession()->getCustomerId();
        $this->load($customerId,'customer_id');
        $quoteId = $this->getId();
        $collection->getSelect()->where(new Zend_Db_Expr("quote_id = {$quoteId}"));
        $items = $collection->getResource()->getReadConnection()->fetchAll($collection->getSelect());
        return $items;
    }
    public function setShippingAdddress()
    {
        
    }
    public function getShippingAdddress()
    {
        $address = Mage::getModel('order/quote_address');
        $collection = $address->getCollection();
        $customerId = $this->_getSession()->getCustomerId();
        $this->load($customerId,'customer_id');
        $quoteId = $this->getId();
        $collection->getSelect()->where(new Zend_Db_Expr("quote_id = {$quoteId} AND customer_id = {$customerId} AND address_type = 'shipping'"));
        $address = $collection->getResource()->getReadConnection()->fetchRow($collection->getSelect());
        return $address;
    }
    public function setBillingAdddress()
    {
        
    }
    public function getBillingAdddress()
    {
        $address = Mage::getModel('order/quote_address');
        $collection = $address->getCollection();
        $customerId = $this->_getSession()->getCustomerId();
        $collection->getSelect()->where(new Zend_Db_Expr("customer_id = {$customerId} AND address_type = 'billing'"));
        $address = $collection->getResource()->getReadConnection()->fetchRow($collection->getSelect());
        return $address;
    }
    public function setBillingMethod()
    {
        
    }
    public function getBillingMethod()
    {
        
    }
}
