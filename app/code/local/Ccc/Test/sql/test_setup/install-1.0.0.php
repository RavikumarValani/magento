<?php 
    $installer = $this;

    $installer->startSetup();

    $table = $installer->getConnection()->newTable($installer->getTable('test/test'))
        ->addColumn('test_id',Varien_Db_Ddl_Table::TYPE_INTEGER,null,array(
            'primary' => true,
            'unsigned' => true,
            'nullable' => false,
            'identity' => true
        ),'Test Id')
        ->addColumn('first_name',
        Varien_Db_Ddl_Table::TYPE_VARCHAR, null,
        array(
            'nullable' => false,
        ), 'first name'
        )
        ->addColumn('last_name',
            Varien_Db_Ddl_Table::TYPE_VARCHAR, null,
            array(
                'nullable' => false,
            ), 'last name'
        );

    $installer->getConnection()->createTable($table);

    $installer->endSetup();

?>