<?php
$installer = $this;
$installer->startSetup();

$table = $installer->getConnection()
		->newTable($installer->getTable('vendor/product_request'))
		->addColumn(
			'request_id',
			Varien_Db_Ddl_Table::TYPE_INTEGER,
			11,
			array(
				'nullable'=>false,
				'identity'=>true,
				'unsigned'  => true,
				'primary'=>true
			), 'Request Id')
		->addColumn(
			'vendor_id',
			Varien_Db_Ddl_Table::TYPE_INTEGER,
			11,
			array(
				'nullable'=>false,
				'unsigned'  => true
			), 'Vendor Id')
		->addColumn(
			'product_id',
			Varien_Db_Ddl_Table::TYPE_INTEGER,
			11,
			array(
				'nullable'=>false,
				'unsigned'  => true
			), 'Product Id')
		->addColumn(
			'catalog_product_id',
			Varien_Db_Ddl_Table::TYPE_INTEGER,
			11,
			array(
				'unsigned' => true,
				'default' => null
			), 'Catalog Product Id')
		->addColumn(
			'request_type',
			Varien_Db_Ddl_Table::TYPE_VARCHAR,
			64,
			array(
				'nullable'=>false
			), 'Request Type')
		->addColumn(
			'request',
			Varien_Db_Ddl_Table::TYPE_VARCHAR,
			64,
			array(
				'nullable'=>false				
			), 'Request')
		->addColumn(
			'approved',
			Varien_Db_Ddl_Table::TYPE_INTEGER,
			11,
			array(
				'default'=> '2'
			), 'approved')
		->addColumn(
			'requested_date',
			Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
			null,
			array(), 'Requested Date')
		->addColumn(
			'request_approved_date',
			Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
			null,
			array(), 'Request Approved Date')
		->addForeignKey(
			$installer->getFkName(
				'vendor/product_request',
				'product_id',
				'vendor/product',
				'entity_id'
			),
			'product_id',$installer->getTable('vendor/product'),'entity_id',
			Varien_Db_Ddl_Table::ACTION_CASCADE,Varien_Db_Ddl_Table::ACTION_CASCADE )
		->addForeignKey(
			$installer->getFkName(
				'vendor/product_request',
				'catalog_product_id',
				'catalog/product',
				'entity_id'
			),
			'catalog_product_id',$installer->getTable('catalog/product'),'entity_id',
			Varien_Db_Ddl_Table::ACTION_CASCADE,Varien_Db_Ddl_Table::ACTION_CASCADE )
		->addForeignKey(
			$installer->getFkName(
				'vendor/product_request',
				'vendor_id',
				'vendor/vendor',
				'entity_id'
			),
			'vendor_id',$installer->getTable('vendor/vendor'),'entity_id',
			Varien_Db_Ddl_Table::ACTION_CASCADE,Varien_Db_Ddl_Table::ACTION_CASCADE )
		    ->setComment('Vendor product crud operation request table');

$installer->getConnection()->createTable($table);
$installer->endSetup();