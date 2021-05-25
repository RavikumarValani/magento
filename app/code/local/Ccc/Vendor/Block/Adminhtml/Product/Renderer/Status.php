<?php
class Ccc_Vendor_Block_Adminhtml_Product_Renderer_Status extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $value = $row->getData('approved');
        if($value == 1)
        {
            return 'Approve';
        }
        else if($value == 0)
        {
            return 'Unapproved';
        }
        else
        {
            return 'Pending';
        }
    }
}