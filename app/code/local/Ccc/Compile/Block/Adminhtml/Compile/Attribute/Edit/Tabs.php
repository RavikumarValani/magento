<?php

class Ccc_Compile_Block_Adminhtml_Compile_Attribute_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('compile_attribute_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('compile')->__('Attribute Information'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('main', array(
            'label'     => Mage::helper('compile')->__('Properties'),
            'title'     => Mage::helper('compile')->__('Properties'),
            'content'   => $this->getLayout()->createBlock('compile/adminhtml_compile_attribute_edit_tab_main')->toHtml(),
            'active'    => true
        ));

        $model = Mage::registry('entity_attribute');

        $this->addTab('labels', array(
            'label'     => Mage::helper('compile')->__('Manage Label / Options'),
            'title'     => Mage::helper('compile')->__('Manage Label / Options'),
            'content'   => $this->getLayout()->createBlock('compile/adminhtml_compile_attribute_edit_tab_options')->toHtml(),
        ));
        
        if ('select' == $model->getFrontendInput()) {
            $this->addTab('options_section', array(
                'label'     => Mage::helper('compile')->__('Options Control'),
                'title'     => Mage::helper('compile')->__('Options Control'),
                'content'   => $this->getLayout()->createBlock('compile/adminhtml_compile_attribute_edit_tab_options')->toHtml(),
            ));
        }

        return parent::_beforeToHtml();
    }

}
