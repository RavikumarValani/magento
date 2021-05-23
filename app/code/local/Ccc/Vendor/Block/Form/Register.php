<?php

    class Ccc_Vendor_Block_Form_Register extends Mage_Directory_Block_Data
    {
        protected $_address;

        protected function _prepareLayout()
        {
            $this->getLayout()->getBlock('head')->setTitle(Mage::helper('vendor')->__('Create New vendor Account'));
            return parent::_prepareLayout();
        }

        public function getPostActionUrl()
        {
            return $this->helper('vendor')->getRegisterPostUrl();
        }

        /**
         * Retrieve back url
         *
         * @return string
         */
        public function getBackUrl()
        {
            $url = $this->getData('back_url');
            if (is_null($url)) {
                $url = $this->helper('vendor')->getLoginUrl();
            }
            return $url;
        }

        /**
         * Retrieve form data
         *
         * @return Varien_Object
         */
        public function getFormData()
        {
            $data = $this->getData('form_data');
            if (is_null($data)) {
                $formData = Mage::getSingleton('vendor/session')->getVendorFormData(true);
                $data = new Varien_Object();
                if ($formData) {
                    $data->addData($formData);
                    $data->setVendorData(1);
                }
                if (isset($data['region_id'])) {
                    $data['region_id'] = (int)$data['region_id'];
                }
                $this->setData('form_data', $data);
            }
            return $data;
        }

        public function getCountryId()
        {
            $countryId = $this->getFormData()->getCountryId();
            if ($countryId) {
                return $countryId;
            }
            return parent::getCountryId();
        }

        public function getRegion()
        {
            if (false !== ($region = $this->getFormData()->getRegion())) {
                return $region;
            } else if (false !== ($region = $this->getFormData()->getRegionId())) {
                return $region;
            }
            return null;
        }

        /**
         *  Newsletter module availability
         *
         *  @return boolean
         */
        public function isNewsletterEnabled()
        {
            return Mage::helper('core')->isModuleOutputEnabled('Mage_Newsletter');
        }

        
        public function getAddress()
        {
            if (is_null($this->_address)) {
                $this->_address = Mage::getModel('customer/address');
            }

            return $this->_address;
        }

        public function restoreSessionData(Ccc_Vendor_Model_Form $form, $scope = null)
        {
            if ($this->getFormData()->getVendorData()) {
                $request = $form->prepareRequest($this->getFormData()->getData());
                $data    = $form->extractData($request, $scope, false);
                $form->restoreData($data);
            }

            return $this;
        }
    }