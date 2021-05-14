<?php

$installer = $this;

$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
$setup->removeAttribute(Ccc_Vendor_Model_Resource_Vendor::ENTITY, 'password_hash');
$setup->removeAttribute(Ccc_Vendor_Model_Resource_Vendor::ENTITY, 'vendor_id');

$setup->addAttribute(Ccc_Vendor_Model_Resource_Vendor::ENTITY, 'password_hash', array(
    'group'                      => 'General',
    'input'                      => 'text',
    'type'                       => 'varchar',
    'label'                      => 'password',
    'frontend_class'             => 'validate-length minimum-length-6',
    'backend'                    => '',
    'visible'                    => 1,
    'required'                   => 1,
    'user_defined'               => 1,
    'searchable'                 => 1,
    'filterable'                 => 0,
    'comparable'                 => 1,
    'visible_on_front'           => 1,
    'visible_in_advanced_search' => 0,
    'is_html_allowed_on_front'   => 1,
    'global'                     => Ccc_Vendor_Model_Resource_Eav_Attribute::SCOPE_STORE,

));

$setup->addAttribute(Ccc_Vendor_Model_Resource_Product::ENTITY, 'vendor_id', array(
    'group'                      => 'General',
    'input'                      => 'text',
    'type'                       => 'int',
    'label'                      => 'vendorId',
    'frontend_class'             => 'validate-digit',
    'backend'                    => '',
    'visible'                    => 1,
    'required'                   => 1,
    'user_defined'               => 1,
    'searchable'                 => 1,
    'filterable'                 => 0,
    'comparable'                 => 1,
    'visible_on_front'           => 1,
    'visible_in_advanced_search' => 0,
    'is_html_allowed_on_front'   => 1,
    'used_in_product_listing' => true,
    'used_for_sort_by' => true,
    'sort_order' => 1,
    'global'                     => Ccc_Vendor_Model_Resource_Eav_Attribute::SCOPE_STORE,
));


$installer->endSetup();
