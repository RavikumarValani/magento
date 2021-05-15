<?php
class Ccc_Vendor_Block_Product_Edit_Tabs_Attributes extends Mage_Core_Block_Template
{
    public function __construct()
    {
        $this->setTemplate('vendor/product/edit/tabs/attributes.phtml');
    }
    public function getAttributeOption($attributeId)
    {
    	$collection = Mage::getResourceModel('eav/entity_attribute_option_collection');
    	$collection->getSelect()->join(
    		array('attribute_option_value'=>'eav_attribute_option_value'),
	    		'attribute_option_value.option_id = main_table.option_id',
    		array('*'))->where("main_table.attribute_id =". $attributeId);
    	return $collection;	
    }
   public function getProduct()
   {
      return Mage::registry('current_product');
   }
}
