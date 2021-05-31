<?php
class Ccc_Order_Model_Order extends Mage_Core_Model_Abstract
{
    
    public function _construct()
    {
        $this->_init('order/order');
    }

    protected $customer = null;
    protected $items = [];
    protected $billingAddress = null;
    protected $shippingAddress = null;
    protected $shippingMethodCode = null;
    protected $billingMethod = null;

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
        $item = Mage::getModel('order/order_item');
        $collection = $item->getCollection();
        $customerId = $this->_getSession()->getCustomerId();
        $this->load($customerId,'customer_id');
        $orderId = $this->getId();
        $collection->getSelect()->where(new Zend_Db_Expr("order_id = {$orderId}"));
        return $collection;
    }
    public function setShippingAddress(Ccc_Order_Model_Order_Address $address)
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
        $address = Mage::getModel('order/order_address');
        $collection = $address->getCollection();
        $type = Ccc_Order_Model_Order_Address::TYPE_SHIPPING;
        $collection->getSelect()->where(new Zend_Db_Expr("customer_id = {$this->getCustomerId()} AND address_type = '{$type}'"));
        $shippingAddress = $address->setData($collection->getResource()->getReadConnection()->fetchRow($collection->getSelect()));
        $this->setShippingAddress($shippingAddress);
        return $this->shippingAddress;
    }
    
    public function setBillingAddress(Ccc_Order_Model_Order_Address $address)
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
        $address = Mage::getModel('order/order_address');
        $collection = $address->getCollection();
        $type = Ccc_Order_Model_Order_Address::TYPE_BILLING;
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
            $subTotal = $subTotal + $item['price']*$item['quantity'];
        }
        return $subTotal;
    }
}