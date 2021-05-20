<?php
 
class Ccc_Payment_Model_Method_Cod extends Mage_Payment_Model_Method_Abstract
{
 
    protected $_code = 'cod';

    protected $_formBlockType = 'payment/form_cashondelivery';
    protected $_infoBlockType = 'payment/info';

    public function getInstructions()
    {
        return trim($this->getConfigData('instructions'));
    }
 
}