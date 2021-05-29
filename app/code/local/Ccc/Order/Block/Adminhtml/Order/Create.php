<?php
class Ccc_Order_Block_Adminhtml_Order_Create extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        $this->_objectId = 'order_id';
        $this->_blockGroup = 'order';
        $this->_controller = 'adminhtml_order';
        $this->_mode = 'create';

        parent::__construct();

        $this->setId('order_create');

        
        $this->_updateButton('save', 'style', 'display:none');
        $this->_updateButton('reset', 'style', 'display:none');

        $this->_updateButton('back', 'id', 'back_order_top_button');
        $this->_updateButton('back', 'onclick', 'setLocation(\'' . $this->getBackUrl() . '\')');

        
        $this->_headerText = $this->getHeaderText();
    }

    public function getHeaderText()
    {
        $out = Mage::helper('order')->__('Create New Order');
        $out = $this->escapeHtml($out);
        $out = '<h3 class="icon-head head-sales-order">' . $out . '</h3>';
        return $out;
    }

    /**
     * Check access for cancel action
     *
     * @return boolean
     */
    protected function _isCanCancel()
    {
        return Mage::getSingleton('admin/session')->isAllowed('order/actions/cancel');
    }


    /**
     * Prepare form html. Add block for configurable product modification interface
     *
     * @return string
     */
   

    public function getHeaderWidth()
    {
        return 'width: 70%;';
    }

    /**
     * Retrieve quote session object
     *
     * @return Mage_Adminhtml_Model_Session_Quote
     */
    protected function _getSession()
    {
        return Mage::getSingleton('order/session_quote');
    }

    public function getCancelUrl()
    {
        if ($this->_getSession()->getOrder()->getId()) {
            $url = $this->getUrl('*/sales_order/view', array(
                'order_id' => Mage::getSingleton('order/session_quote')->getOrder()->getId()
            ));
        } else {
            $url = $this->getUrl('*/adminhtml_order/cancel');
        }

        return $url;
    }

    /**
     * Get URL for back (reset) button
     *
     * @return string
     */
    public function getBackUrl()
    {
        return $this->getUrl('*/' . $this->_controller . '/');
    }
}
