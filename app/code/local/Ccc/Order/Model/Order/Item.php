<?php
class Ccc_Order_Model_Order_Item extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('order/order_item');
    }

    protected function _getSession()
    {
        return Mage::getSingleton('order/session_quote');
    }

    public function getQuoteId()
    {
        $quote = Mage::getModel('order/quote');
        $collection = $quote->getCollection();
        $collection->getSelect()->where('customer_id = ?', $this->_getSession()->getCustomerId())->reset(Zend_Db_Select::COLUMNS)->columns('entity_id');
        $quoteId = $collection->getColumnValues('entity_id')[0];
        return $quoteId;
    }
}
