<?php
class Ccc_Order_Model_Quote_Address extends Mage_Customer_Model_Address_Abstract
{
    protected $_eventPrefix = 'order_quote_address';

    protected $_eventObject = 'quote_address';

    const TYPE_SHIPPING = 'shipping';
    const TYPE_BILLING = 'billing';

    protected $quote;
    protected $customerBillingAddress;
    protected $customerShippingAddress;

    protected function _construct()
    {
        $this->_init('order/quote_address');
    }

    public function setCustomerShippingAddress(Mage_Customer_Model_Customer $address)
    {
        $this->customerShippingAddress = $address;
        return $this;      
    }

    public function getCustomerShippingAddress()
    {
        $customerId = $this->getQuote()->getCustomer()->getId();
        $collection = $this->getCollection();
        $collection->getSelect()->where(new Zend_Db_Expr("customer_id = {$customerId} AND address_type={self::TYPE_SHIPPING}"));
        $address = $collection->getResource()->getReadConnection()->fetchRow($collection->getSelect());
        $customerAddress = Mage::getModel('customer/address')->setData($address);
        $this->setCustomerShippingAddress($customerAddress);

        return $this->customerShippingAddress;
    }

    public function setCustomerBillingAddress(Mage_Customer_Model_Customer $address)
    {
        $this->customerBillingAddress = $address;
        return $this;
    }

    public function getCustomerBillingAddress()
    {
        $customerId = $this->_getSession()->getCustomerId();
        $collection = $this->getCollection();
        $collection->getSelect()->where(new Zend_Db_Expr("customer_id = {$customerId} AND address_type='shipping'"));
        $address = $collection->getResource()->getReadConnection()->fetchRow($collection->getSelect());
        $customerAddress = Mage::getModel('customer/address')->setData($address);
        $this->setCustomerBillingAddress($customerAddress);
        return $this->customerBillingAddress;
    }

    public function setQuote(Ccc_Order_Model_Quote $quote)
    {
        $this->quote = $quote;
        return $this;
    }

    public function getQuote()
    {
        return $this->quote;
    }
 
}
