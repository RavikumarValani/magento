<?php

$this->startSetup();

$this->addEntityType(Ccc_Compile_Model_Resource_Compile::ENTITY, [
    'entity_model'                => 'compile/compile',
    'attribute_model'             => 'compile/attribute',
    'table'                       => 'compile/compile',
    'increment_per_store'         => '0',
    'additional_attribute_table'  => 'compile/eav_attribute',
    'entity_attribute_collection' => 'compile/compile_attribute_collection',
]);

$this->createEntityTables('compile');
$this->installEntities();

$default_attribute_set_id = Mage::getModel('eav/entity_setup', 'core_setup')
    						->getAttributeSetId('compile', 'Default');

$this->run("UPDATE `eav_entity_type` SET `default_attribute_set_id` = {$default_attribute_set_id} WHERE `entity_type_code` = 'compile'");

$this->endSetup();
