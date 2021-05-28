<?php
class Ccc_Order_Model_Quote_Address extends Mage_Customer_Model_Address_Abstract
{
    protected $_eventPrefix = 'order_quote_address';

    protected $_eventObject = 'quote_address';

    
    protected function _construct()
    {
        $this->_init('order/quote_address');
    }


    public function getQuoteId()
    {
        $quote = Mage::getModel('order/quote');
        $collection = $quote->getCollection();
        $collection->getSelect()->where('customer_id = ?', $this->_getSession()->getCustomerId())->reset(Zend_Db_Select::COLUMNS)->columns('entity_id');
        $quoteId = $collection->getColumnValues('entity_id')[0];
        return $quoteId;
    }

    protected function _getSession()
    {
        return Mage::getSingleton('order/session_quote');
    }

    public function setShippingAddress($address)
    {
        if($this->getShippingAddress())
        {
            $this->EntityId = $this->getShippingAddress()['entity_id'];
        }
        
    }

    public function getShippingAddress()
    {
        $customerId = $this->_getSession()->getCustomerId();
        $collection = $this->getCollection();
        $collection->getSelect()->where(new Zend_Db_Expr("customer_id = {$customerId} AND address_type='shipping'"));
        $address = $collection->getResource()->getReadConnection()->fetchRow($collection->getSelect());
        return $address;
    }

    public function getBillingAddress()
    {
        $customerId = $this->_getSession()->getCustomerId();
        $collection = $this->getCollection();
        $collection->getSelect()->where(new Zend_Db_Expr("customer_id = {$customerId} AND address_type='billing'"));
        $address = $collection->getResource()->getReadConnection()->fetchRow($collection->getSelect());
        return $address;
    }

   
}
