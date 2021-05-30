<?php
class Ccc_Order_Model_Quote extends Mage_Core_Model_Abstract
{
    protected $customer = null;
    protected $items = [];
    protected $billingAddress = null;
    protected $shippingAddress = null;
    protected $shippingMethodCode = null;
    protected $billingMethod = null;

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
        if(!$this->getCustomerId())
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
        $quoteId = $this->getId();
        $collection->getSelect()->where(new Zend_Db_Expr("quote_id = {$quoteId}"));
        return $collection;
    }
    public function setShippingAddress(Ccc_Order_Model_Quote_Address $address)
    {
        $this->shippingAddress = $address;
        return $this;
    }
    public function getShippingAddress()
    {
        if(!$this->getId())
        {
            return false;
        }
        $address = Mage::getModel('order/quote_address');
        $collection = $address->getCollection();
        $type = Ccc_Order_Model_Quote_Address::TYPE_SHIPPING;
        $collection->getSelect()->where(new Zend_Db_Expr("customer_id = {$this->getCustomerId()} AND address_type = '{$type}'"));
        $shippingAddress = $address->setData($collection->getResource()->getReadConnection()->fetchRow($collection->getSelect()));
        $this->setShippingAddress($shippingAddress);
        return $this->shippingAddress;
    }
    
    public function setBillingAddress(Ccc_Order_Model_Quote_Address $address)
    {
        $this->billingAddress = $address;
        return $this;
    }
    public function getBillingAddress()
    {
        if(!$this->getId())
        {
            return false;
        }
        $address = Mage::getModel('order/quote_address');
        $collection = $address->getCollection();
        $type = Ccc_Order_Model_Quote_Address::TYPE_BILLING;
        $collection->getSelect()->where(new Zend_Db_Expr("customer_id = {$this->getCustomerId()} AND address_type = '{$type}'"));
        $billingAddress = $address->setData($collection->getResource()->getReadConnection()->fetchRow($collection->getSelect()));
        $this->setBillingAddress($billingAddress);
        return $this->billingAddress;
    }

    public function setBillingMethod($method)
    {
        $this->billingMethod = $method;
        return $this;
    }
    public function getBillingMethod()
    {
        $this->setBillingMethod($this->getPaymentMethod());
        return $this->billingMethod;
    }

    public function setShippingMethodCode($method)
    {
        $this->shippingMethodCode = $method;
        return $this;
    }
    public function getShippingMethodCode()
    {
        $this->setShippingMethodCode($this->getShippingMethod());
        return $this->shippingMethodCode;
    }

    public function getSubTotal()
    {
        $subTotal = 0;
        $items = $this->getItems();
        foreach($items as $item)
        {
            $subTotal = $subTotal + $item->getPrice()*$item->getQuantity();
        }
        return $subTotal;
    }
}
