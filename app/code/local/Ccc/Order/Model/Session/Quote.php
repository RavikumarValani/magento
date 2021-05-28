<?php
class Ccc_Order_Model_Session_Quote extends Mage_Core_Model_Session_Abstract
{
    const XML_PATH_DEFAULT_CREATEACCOUNT_GROUP = 'customer/create_account/default_group';

    /**
     * Quote model object
     *
     * @var Mage_Sales_Model_Quote
     */
    protected $_quote   = null;

    /**
     * Customer mofrl object
     *
     * @var Mage_Customer_Model_Customer
     */
    protected $_customer= null;

    /**
     * Store model object
     *
     * @var Mage_Core_Model_Store
     */
    protected $_store   = null;

    /**
     * Order model object
     *
     * @var Mage_Sales_Model_Order
     */
    protected $_order   = null;

    public function __construct()
    {
        $this->init('order_quote');
        if (Mage::app()->isSingleStoreMode()) {
            $this->setStoreId(Mage::app()->getStore(true)->getId());
        }
    }

    /**
     * Retrieve quote model object
     *
     * @return Mage_Sales_Model_Quote
     */
    public function getQuote()
    {
        $this->_quote = Mage::getModel('order/quote');
        $collection = $this->_quote->getCollection();
        $collection->getSelect()->reset(Zend_Db_Select::COLUMNS)->where('customer_id = ?', $this->getCustomerId())->columns(['entity_id']);
        $quoteId = $collection->getResource()->getReadConnection()->fetchOne($collection->getSelect());
        $this->_quote->load($quoteId);
        return $this->_quote;
    }

    /**
     * Set customer model object
     * To enable quick switch of preconfigured customer
     * @param Mage_Customer_Model_Customer $customer
     * @return Mage_Adminhtml_Model_Session_Quote
     */
    public function setCustomer(Mage_Customer_Model_Customer $customer)
    {
        $this->_customer = $customer;
        return $this;
    }

/**
     * Retrieve customer model object
     * @param bool $forceReload
     * @param bool $useSetStore
     * @return Mage_Customer_Model_Customer
     */
    public function getCustomer($forceReload=false, $useSetStore=false)
    {
        if (is_null($this->_customer) || $forceReload) {
            $this->_customer = Mage::getModel('customer/customer');
            if ($useSetStore && $this->getStore()->getId()) {
                $this->_customer->setStore($this->getStore());
            }
            if ($customerId = $this->getCustomerId()) {
                $this->_customer->load($customerId);
            }
        }
        return $this->_customer;
    }

    /**
     * Retrieve store model object
     *
     * @return Mage_Core_Model_Store
     */
    public function getStore()
    {
        if (is_null($this->_store)) {
            $this->_store = Mage::app()->getStore($this->getStoreId());
            if ($currencyId = $this->getCurrencyId()) {
                $this->_store->setCurrentCurrencyCode($currencyId);
            }
        }
        return $this->_store;
    }

    /**
     * Retrieve order model object
     *
     * @return Mage_Sales_Model_Order
     */
    public function getOrder()
    {
        if (is_null($this->_order)) {
            $this->_order = Mage::getModel('order/order');
            if ($this->getOrderId()) {
                $this->_order->load($this->getOrderId());
            }
        }
        return $this->_order;
    }
}
