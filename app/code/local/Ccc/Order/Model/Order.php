<?php
class Ccc_Order_Model_Order extends Mage_Core_Model_Abstract
{
    
    public function _construct()
    {
        $this->_init('order/order');
    }

    public function formatPrice($price, $addBrackets = false)
    {
        return $this->formatPricePrecision($price, 2, $addBrackets);
    }
}