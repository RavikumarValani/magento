<?php
class Ccc_Order_Model_Resource_Quote extends Mage_Core_Model_Resource_Db_Abstract
{
    public function _construct()
    {
        $this->_init('order/quote', 'entity_id');

    }

}