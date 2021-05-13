<?php
class Ccc_Vendor_Block_Adminhtml_Product_Attribute_Edit_Tab_Front extends Mage_Adminhtml_Block_Widget_Form
{

    protected function _prepareForm()
    {
        $model = Mage::registry('vendor_product');

        $form = new Varien_Data_Form(array('id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post'));

        $fieldset = $form->addFieldset('base_fieldset', array('legend'=>Mage::helper('vendor')->__('Frontend Properties')));

        $yesno = array(
            array(
                'value' => 0,
                'label' => Mage::helper('vendor')->__('No')
            ),
            array(
                'value' => 1,
                'label' => Mage::helper('vendor')->__('Yes')
            ));


        $fieldset->addField('is_searchable', 'select', array(
            'name' => 'is_searchable',
            'label' => Mage::helper('vendor')->__('Use in Quick Search'),
            'title' => Mage::helper('vendor')->__('Use in Quick Search'),
            'values' => $yesno,
        ));

        $fieldset->addField('is_visible_in_advanced_search', 'select', array(
            'name' => 'is_visible_in_advanced_search',
            'label' => Mage::helper('vendor')->__('Use in Advanced Search'),
            'title' => Mage::helper('vendor')->__('Use in Advanced Search'),
            'values' => $yesno,
        ));

        $fieldset->addField('is_comparable', 'select', array(
            'name' => 'is_comparable',
            'label' => Mage::helper('vendor')->__('Comparable on the Frontend'),
            'title' => Mage::helper('vendor')->__('Comparable on the Frontend'),
            'values' => $yesno,
        ));


        $fieldset->addField('is_filterable', 'select', array(
            'name' => 'is_filterable',
            'label' => Mage::helper('vendor')->__("Use in Layered Navigation<br/>(Can be used only with vendor input type 'Dropdown')"),
            'title' => Mage::helper('vendor')->__('Can be used only with vendor input type Dropdown'),
            'values' => array(
                array('value' => '0', 'label' => Mage::helper('vendor')->__('No')),
                array('value' => '1', 'label' => Mage::helper('vendor')->__('Filterable (with results)')),
                array('value' => '2', 'label' => Mage::helper('vendor')->__('Filterable (no results)')),
            ),
        ));

//        if ($model->getIsUserDefined() || !$model->getId()) {
            $fieldset->addField('is_visible_on_front', 'select', array(
                'name' => 'is_visible_on_front',
                'label' => Mage::helper('vendor')->__('Visible on vendor Pages on Front-end'),
                'title' => Mage::helper('vendor')->__('Visible on vendor Pages on Front-end'),
                'values' => $yesno,
            ));
//        }

        $form->setValues($model->getData());

        $this->setForm($form);

        return parent::_prepareForm();
    }

}
