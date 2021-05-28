<?php
class Ccc_Order_Block_Adminhtml_Order_Quote_Shipping_Address extends Mage_Adminhtml_Block_Widget_Form
{
    public function getHeaderText()
    {
        return Mage::helper('sales')->__('Shipping Address');
    }

    public function getShippingAddress()
    {
        $address = Mage::getModel('order/quote_address');
        $collection = $address->getCollection();
        $customerId = Mage::getSingleton('order/session_quote')->getCustomerId();
        $collection->getSelect()->reset(Zend_Db_Select::WHERE)->where(new Zend_Db_Expr("customer_id = {$customerId} AND address_type='shipping'"));
        $address = $collection->getResource()->getReadConnection()->fetchRow($collection->getSelect());
        if($address)
        {
            return $address;
        }
        else
        {
            $customerCollection = Mage::getModel('customer/address')->getCollection();
            $customerCollection->addAttributeToSelect(['city', 'firstname', 'lastname', 'country_id', 'postcode', 'region', 'street'], 'inner');
            $customerCollection->getSelect()
                ->reset(Zend_Db_Select::COLUMNS)
                ->columns(['e.entity_id', 'city' => 'at_city.value', 'firstname' => 'at_firstname.value', 'lastname' => 'at_lastname.value', 'country_id' => 'at_country_id.value', 'postcode' => 'at_postcode.value', 'street' => 'at_street.value', 'region' => 'at_region.value']);
            $customerCollection->addFieldToFilter('entity_id', Mage::getSingleton('order/session_quote')->getCustomerId());
            $address = $customerCollection->getResource()->getReadConnection()->fetchRow($customerCollection->getSelect());
            return $address;
        }
    }

    /**
     * Return Header CSS Class
     *
     * @return string
     */
    public function getHeaderCssClass()
    {
        return 'head-shipping-address';
    }
    
    public function getCountry()
    {
        return Mage::getBlockSingleton('directory/data')->getCountryHtmlSelect();
    }

    
}
