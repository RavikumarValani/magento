<?php
class Ccc_Order_Model_Order_Address extends Mage_Customer_Model_Address_Abstract
{
    protected $_eventPrefix = 'order_address';

    protected $_eventObject = 'address';

    protected function _construct()
    {
        $this->_init('order/order_address');
    }

}
