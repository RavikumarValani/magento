<?php
$installer = $this;
$installer->startSetup();
//order table
$table = $installer->getConnection()
    ->newTable($installer->getTable('order/order'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity' => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Entity Id')
    ->addColumn('customer_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        ), 'Customer Id')
        ->addColumn('shipping_name', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        ), 'Shipping Name')
        ->addColumn('billing_name', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        ), 'Billing Name')
        ->addColumn('shipping_method', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        ), 'Shipping Method')
        ->addColumn('payment_method', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        ), 'Payment Method')
        ->addColumn('shipping_amount', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        ), 'Shipping Amount')
        ->addColumn('base_grand_total', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array(
            ), 'Base Grand Total')
        ->addColumn('discount', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array(
            ), 'Discount')
        ->addColumn('grand_total', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array(
            ), 'Grand Total')
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        ), 'Created At')
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        ), 'Updated At')
    ->addIndex($installer->getIdxName('order/order', array('base_grand_total')),
        array('base_grand_total'))
    ->addIndex($installer->getIdxName('order/order', array('grand_total')),
        array('grand_total'))
    ->addIndex($installer->getIdxName('order/order', array('discount')),
        array('discount'))
    ->addIndex($installer->getIdxName('order/order', array('shipping_name')),
        array('shipping_name'))
    ->addIndex($installer->getIdxName('order/order', array('billing_name')),
        array('billing_name'))
    ->addIndex($installer->getIdxName('order/order', array('created_at')),
        array('created_at'))
    ->addIndex($installer->getIdxName('order/order', array('customer_id')),
        array('customer_id'))
    ->addIndex($installer->getIdxName('order/order', array('updated_at')),
        array('updated_at'))
    ->addForeignKey($installer->getFkName('order/order', 'customer_id', 'customer/entity', 'entity_id'),
        'customer_id', $installer->getTable('customer/entity'), 'entity_id',
        Varien_Db_Ddl_Table::ACTION_SET_NULL, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Ccc Flat Order Grid');
$installer->getConnection()->createTable($table);


$installer->endSetup();
