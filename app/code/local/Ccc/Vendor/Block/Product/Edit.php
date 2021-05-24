<?php
class Ccc_Vendor_Block_Product_Edit extends Mage_Core_Block_Template
{
    public function prepareLayout()
    {
        return Mage::getBlockSingleton('vendor/product_edit_form');
    }
}
