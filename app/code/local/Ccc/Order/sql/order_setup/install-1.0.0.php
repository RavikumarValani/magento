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
        ->addColumn('status', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        ), 'Status')
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

$table = $installer->getConnection()
    ->newTable($installer->getTable('order/order_address'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Entity Id')
    ->addColumn('customer_address_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        ), 'Customer Address Id')
    ->addColumn('customer_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        ), 'Customer Id')
    ->addColumn('order_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        ), 'Order Id')
    ->addColumn('fax', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        ), 'Fax')
    ->addColumn('region', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        ), 'Region')
    ->addColumn('postcode', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        ), 'Postcode')
    ->addColumn('lastname', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        ), 'Lastname')
    ->addColumn('street', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        ), 'Street')
    ->addColumn('city', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        ), 'City')
    ->addColumn('email', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        ), 'Email')
    ->addColumn('telephone', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        ), 'Telephone')
    ->addColumn('country_id', Varien_Db_Ddl_Table::TYPE_TEXT, 2, array(
        ), 'Country Id')
    ->addColumn('firstname', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        ), 'Firstname')
    ->addColumn('address_type', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        ), 'Address Type')
    ->addColumn('prefix', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        ), 'Prefix')
    ->addColumn('middlename', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        ), 'Middlename')
    ->addColumn('suffix', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        ), 'Suffix')
    ->addColumn('company', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        ), 'Company')
    ->addForeignKey($installer->getFkName('order/order_address', 'order_id', 'order/order', 'entity_id'),
        'order_id', $installer->getTable('order/order'), 'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Order Address');
$installer->getConnection()->createTable($table);

$table = $installer->getConnection()
    ->newTable($installer->getTable('order/order_item'))
    ->addColumn('item_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Item Id')
    ->addColumn('order_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Order Id')
    ->addColumn('product_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        ), 'Product Id')
    ->addColumn('sku', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        ), 'Sku')
    ->addColumn('name', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        ), 'Name')
    ->addColumn('description', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(
        ), 'Description')
    ->addColumn('price', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array(
        'nullable'  => false,
        'default'   => '0.0000',
        ), 'Price')
    ->addColumn('quantity', Varien_Db_Ddl_Table::TYPE_INTEGER,null, array(
        'nullable'  => false,
        ), 'Quantity')
    ->addColumn('discount_amount', Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4', array(
        'default'   => '0.0000',
        ), 'Discount Amount')
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable'  => false,
        ), 'Created At')
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable'  => false,
        ), 'Updated At')
    ->addForeignKey($installer->getFkName('order/order_item', 'order_id', 'order/order', 'entity_id'),
        'order_id', $installer->getTable('order/order'), 'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Order Item');
$installer->getConnection()->createTable($table);


$installer->endSetup();
