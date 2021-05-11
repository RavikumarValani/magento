<?php

class Ccc_Vendor_Helper_Data extends Mage_Core_Helper_Abstract 
{
    const REFERER_QUERY_PARAM_NAME = 'referer';
    const XML_PATH_CUSTOMER_STARTUP_REDIRECT_TO_DASHBOARD = 'customer/startup/redirect_dashboard';
    const ROUTE_ACCOUNT_LOGIN = 'vendor/account/login';

	public function getAccountUrl()
    {
        return $this->_getUrl('vendor/account');
    }

	public function getLogoutUrl()
    {
        return $this->_getUrl('vendor/account/logout');
    }

    
    public function getLoginUrl()
    {
        return $this->_getUrl(self::ROUTE_ACCOUNT_LOGIN, $this->getLoginUrlParams());
    }
    
    public function getDashboardUrl()
    {
        return $this->_getUrl('vendor/account');
    }
    
    public function getProductAddUrl()
    {
        return $this->_getUrl('vendor/product/new');
    }

    public function getProductSaveUrl()
    {
        return $this->_getUrl('vendor/product/save');
    }

    public function getLoginUrlParams()
    {
        $params = array();
        
        $referer = $this->_getRequest()->getParam(self::REFERER_QUERY_PARAM_NAME);
        
        if (!$referer && !Mage::getStoreConfigFlag(self::XML_PATH_CUSTOMER_STARTUP_REDIRECT_TO_DASHBOARD)
        && !Mage::getSingleton('vendor/session')->getNoReferer()
        ) {
            $referer = Mage::getUrl('*/*/*', array('_current' => true, '_use_rewrite' => true));
            $referer = Mage::helper('core')->urlEncode($referer);
        }
        
        if ($referer) {
            $params = array(self::REFERER_QUERY_PARAM_NAME => $referer);
        }
        
        return $params;
    }
    
    public function getRegisterUrl()
    {
        return $this->_getUrl('vendor/account/create');
    }
    
    public function getRegisterPostUrl()
    {
        return $this->_getUrl('vendor/account/createpost');
    }

    public function isRegistrationAllowed()
    {
        $result = new Varien_Object(array('is_allowed' => true));
        Mage::dispatchEvent('customer_registration_is_allowed', array('result' => $result));
        return $result->getIsAllowed();
    }

    public function getForgotPasswordUrl()
    {
        return $this->_getUrl('vendor/account/forgotpassword');
    }

    public function getLoginPostUrl()
    {
        $params = array();
        if ($this->_getRequest()->getParam(self::REFERER_QUERY_PARAM_NAME)) {
            $params = array(
                self::REFERER_QUERY_PARAM_NAME => $this->_getRequest()->getParam(self::REFERER_QUERY_PARAM_NAME)
            );
        }
        return $this->_getUrl('vendor/account/loginPost', $params);
    }

    public function getEmailConfirmationUrl($email = null)
    {
        return $this->_getUrl('vendor/account/confirmation', array('email' => $email));
    }

}