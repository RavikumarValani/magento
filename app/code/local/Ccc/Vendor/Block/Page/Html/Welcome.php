<?php 
    class Ccc_Vendor_Block_Page_Html_Welcome extends Mage_Page_Block_Html_Welcome
    {
        protected function _toHtml()
        {
            if (empty($this->_data['welcome'])) {
                if (Mage::isInstalled() && Mage::getSingleton('vendor/session')->isLoggedIn()) {
                    $this->_data['welcome'] = $this->__('Welcome, %s!', $this->escapeHtml(Mage::getSingleton('vendor/session')->getVendor()->getName()));
                } else {
                    $this->_data['welcome'] = Mage::getStoreConfig('design/header/welcome');
                }
            }

            return $this->_data['welcome'];
        }
    }
?>