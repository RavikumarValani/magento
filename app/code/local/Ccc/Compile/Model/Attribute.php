<?php

class Ccc_Compile_Model_Attribute extends Mage_Eav_Model_Attribute
{
    const MODULE_NAME = 'Ccc_Compile';

    protected $_eventObject = 'attribute';

    protected function _construct()
    {
        $this->_init('compile/attribute');
    }
}
