<?php
$installer = $this;

$installer->startSetup();

$table = $installer->getConnection()
    ->newTable($installer->getTable('vendor/product_attribute_group'))
    ->addColumn('group_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned' => true,
        'identity' => true,
        'nullable' => false,
        'primary' => true,
    ), 'Attribute Group Id')
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned' => true,
        'nullable' => false,
    ), 'Entity ID')
    ->addColumn('attribute_group_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned' => true,
        'nullable' => false,
        'default' => '0',
    ), 'Attribute Group ID')
    ->addColumn('attribute_group_name', Varien_Db_Ddl_Table::TYPE_VARCHAR, null, array(
        'unsigned' => true,
        'nullable' => false,
    ), 'Attribute Set ID')

    ->addForeignKey(
        $installer->getFkName(
            'vendor/product_attribute_group',
            'attribute_group_id',
            'eav/attribute_group',
            'attribute_group_id'
        ),
        'attribute_group_id', $installer->getTable('eav/attribute_group'), 'attribute_group_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)

    ->addForeignKey(
        $installer->getFkName(
            'vendor/product_attribute_group',
            'entity_id',
            'vendor/vendor',
            'entity_id'
        ),
        'entity_id', $installer->getTable('vendor/vendor'), 'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Product Attribute Group Table');

$installer->getConnection()->createTable($table);

$installer->endSetup();