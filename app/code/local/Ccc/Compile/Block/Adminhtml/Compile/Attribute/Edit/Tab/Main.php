<?php

class Ccc_Compile_Block_Adminhtml_Compile_Attribute_Edit_Tab_Main
    extends Mage_Eav_Block_Adminhtml_Attribute_Edit_Main_Abstract
{
    protected function _prepareForm()
    {
        parent::_prepareForm();

        $attributeObject = $this->getAttributeObject();

        $form = $this->getForm();
        $fieldset = $form->getElement('base_fieldset');
        $fieldset->getElements()
            ->searchById('attribute_code')
            ->setData(
                'class',
                'validate-code-event ' . $fieldset->getElements()->searchById('attribute_code')->getData('class')
            )->setData(
                'note',
                $fieldset->getElements()->searchById('attribute_code')->getData('note')
                . Mage::helper('eav')->__('. Do not use "event" for an attribute code, it is a reserved keyword.')
            );
        $frontendInputElm = $form->getElement('frontend_input');
        $additionalTypes = array(
            array(
                'value' => 'price',
                'label' => Mage::helper('compile')->__('Price')
            ),
            array(
                'value' => 'media_image',
                'label' => Mage::helper('compile')->__('Media Image')
            )
        );
        if ($attributeObject->getFrontendInput() == 'gallery') {
            $additionalTypes[] = array(
                'value' => 'gallery',
                'label' => Mage::helper('compile')->__('Gallery')
            );
        }

        $response = new Varien_Object();
        $response->setTypes(array());
      
        $_disabledTypes = array();
        $_hiddenFields = array();
        foreach ($response->getTypes() as $type) {
            $additionalTypes[] = $type;
            if (isset($type['hide_fields'])) {
                $_hiddenFields[$type['value']] = $type['hide_fields'];
            }
            if (isset($type['disabled_types'])) {
                $_disabledTypes[$type['value']] = $type['disabled_types'];
            }
        }
        Mage::register('attribute_type_hidden_fields', $_hiddenFields);
        Mage::register('attribute_type_disabled_types', $_disabledTypes);

        $frontendInputValues = array_merge($frontendInputElm->getValues(), $additionalTypes);
        $frontendInputElm->setValues($frontendInputValues);

        $yesnoSource = Mage::getModel('adminhtml/system_config_source_yesno')->toOptionArray();

        $scopes = array(
            Ccc_Compile_Model_Resource_Eav_Attribute::SCOPE_STORE =>Mage::helper('compile')->__('Store View'),
            Ccc_Compile_Model_Resource_Eav_Attribute::SCOPE_WEBSITE =>Mage::helper('compile')->__('Website'),
            Ccc_Compile_Model_Resource_Eav_Attribute::SCOPE_GLOBAL =>Mage::helper('compile')->__('Global'),
        );

        if (
            $attributeObject->getAttributeCode() == 'status'
            || $attributeObject->getAttributeCode() == 'tax_class_id'
        ) {
            unset($scopes[Ccc_Compile_Model_Resource_Eav_Attribute::SCOPE_STORE]);
        }

        $fieldset->addField('is_global', 'select', array(
            'name'  => 'is_global',
            'label' => Mage::helper('compile')->__('Scope'),
            'title' => Mage::helper('compile')->__('Scope'),
            'note'  => Mage::helper('compile')->__('Declare attribute value saving scope'),
            'values'=> $scopes
        ), 'attribute_code');


        // frontend properties fieldset
        $fieldset = $form->addFieldset('front_fieldset', array('legend'=>Mage::helper('compile')->__('Frontend Properties')));

        $fieldset->addField('is_searchable', 'select', array(
            'name'     => 'is_searchable',
            'label'    => Mage::helper('compile')->__('Use in Quick Search'),
            'title'    => Mage::helper('compile')->__('Use in Quick Search'),
            'values'   => $yesnoSource,
        ));

        $fieldset->addField('is_visible_in_advanced_search', 'select', array(
            'name' => 'is_visible_in_advanced_search',
            'label' => Mage::helper('compile')->__('Use in Advanced Search'),
            'title' => Mage::helper('compile')->__('Use in Advanced Search'),
            'values' => $yesnoSource,
        ));

        $fieldset->addField('is_comparable', 'select', array(
            'name' => 'is_comparable',
            'label' => Mage::helper('compile')->__('Comparable on Front-end'),
            'title' => Mage::helper('compile')->__('Comparable on Front-end'),
            'values' => $yesnoSource,
        ));

        $fieldset->addField('is_filterable', 'select', array(
            'name' => 'is_filterable',
            'label' => Mage::helper('compile')->__("Use In Layered Navigation"),
            'title' => Mage::helper('compile')->__('Can be used only with catalog input type Dropdown, Multiple Select and Price'),
            'note' => Mage::helper('compile')->__('Can be used only with catalog input type Dropdown, Multiple Select and Price'),
            'values' => array(
                array('value' => '0', 'label' => Mage::helper('compile')->__('No')),
                array('value' => '1', 'label' => Mage::helper('compile')->__('Filterable (with results)')),
                array('value' => '2', 'label' => Mage::helper('compile')->__('Filterable (no results)')),
            ),
        ));

        $fieldset->addField('is_filterable_in_search', 'select', array(
            'name' => 'is_filterable_in_search',
            'label' => Mage::helper('compile')->__("Use In Search Results Layered Navigation"),
            'title' => Mage::helper('compile')->__('Can be used only with catalog input type Dropdown, Multiple Select and Price'),
            'note' => Mage::helper('compile')->__('Can be used only with catalog input type Dropdown, Multiple Select and Price'),
            'values' => $yesnoSource,
        ));

        $fieldset->addField('is_used_for_promo_rules', 'select', array(
            'name' => 'is_used_for_promo_rules',
            'label' => Mage::helper('compile')->__('Use for Promo Rule Conditions'),
            'title' => Mage::helper('compile')->__('Use for Promo Rule Conditions'),
            'values' => $yesnoSource,
        ));

        $fieldset->addField('position', 'text', array(
            'name' => 'position',
            'label' => Mage::helper('compile')->__('Position'),
            'title' => Mage::helper('compile')->__('Position in Layered Navigation'),
            'note' => Mage::helper('compile')->__('Position of attribute in layered navigation block'),
            'class' => 'validate-digits',
        ));

        $fieldset->addField('is_wysiwyg_enabled', 'select', array(
            'name' => 'is_wysiwyg_enabled',
            'label' => Mage::helper('compile')->__('Enable WYSIWYG'),
            'title' => Mage::helper('compile')->__('Enable WYSIWYG'),
            'values' => $yesnoSource,
        ));

        $htmlAllowed = $fieldset->addField('is_html_allowed_on_front', 'select', array(
            'name' => 'is_html_allowed_on_front',
            'label' => Mage::helper('compile')->__('Allow HTML Tags on Frontend'),
            'title' => Mage::helper('compile')->__('Allow HTML Tags on Frontend'),
            'values' => $yesnoSource,
        ));
        if (!$attributeObject->getId() || $attributeObject->getIsWysiwygEnabled()) {
            $attributeObject->setIsHtmlAllowedOnFront(1);
        }

        $fieldset->addField('is_visible_on_front', 'select', array(
            'name'      => 'is_visible_on_front',
            'label'     => Mage::helper('compile')->__('Visible on Product View Page on Front-end'),
            'title'     => Mage::helper('compile')->__('Visible on Product View Page on Front-end'),
            'values'    => $yesnoSource,
        ));

        $fieldset->addField('used_in_product_listing', 'select', array(
            'name'      => 'used_in_product_listing',
            'label'     => Mage::helper('compile')->__('Used in Product Listing'),
            'title'     => Mage::helper('compile')->__('Used in Product Listing'),
            'note'      => Mage::helper('compile')->__('Depends on design theme'),
            'values'    => $yesnoSource,
        ));
        $fieldset->addField('used_for_sort_by', 'select', array(
            'name'      => 'used_for_sort_by',
            'label'     => Mage::helper('compile')->__('Used for Sorting in Product Listing'),
            'title'     => Mage::helper('compile')->__('Used for Sorting in Product Listing'),
            'note'      => Mage::helper('compile')->__('Depends on design theme'),
            'values'    => $yesnoSource,
        ));

        

        // define field dependencies
        $this->setChild('form_after', $this->getLayout()->createBlock('adminhtml/widget_form_element_dependence')
            ->addFieldMap("is_wysiwyg_enabled", 'wysiwyg_enabled')
            ->addFieldMap("is_html_allowed_on_front", 'html_allowed_on_front')
            ->addFieldMap("frontend_input", 'frontend_input_type')
            ->addFieldDependence('wysiwyg_enabled', 'frontend_input_type', 'textarea')
            ->addFieldDependence('html_allowed_on_front', 'wysiwyg_enabled', '0')
        );

      

        return $this;
    }

}
