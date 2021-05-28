<?php
class Ccc_Order_Block_Adminhtml_Order_Quote_Items extends Ccc_Order_Block_Adminhtml_Order_Create_Abstract
{
    /**
     * Contains button descriptions to be shown at the top of accordion
     * @var array
     */
    protected $_buttons = array();

    /**
     * Define block ID
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('order_create_items');
    }

    /**
     * Accordion header text
     *
     * @return string
     */
    public function getHeaderText()
    {
        return Mage::helper('order')->__('Items Ordered');
    }

    

    /**
     * Add button to the items header
     *
     * @param $args array
     */
    public function addButton($args)
    {
        $this->_buttons[] = $args;
    }

    /**
     * Render buttons and return HTML code
     *
     * @return string
     */
    public function getButtonsHtml()
    {
        $addButtonData = array(
            'label' => Mage::helper('order')->__('Add Produc'),
            'onclick' => "showAction()",
            'class' => 'add',
        );
        return $this->getLayout()->createBlock('adminhtml/widget_button')->setData($addButtonData)->toHtml();
    }

    /**
     * Return HTML code of the block
     *
     * @return string
     */
    protected function _toHtml()
    {
        if ($this->getStoreId()) {
            return parent::_toHtml();
        }
        return '';
    }

}
