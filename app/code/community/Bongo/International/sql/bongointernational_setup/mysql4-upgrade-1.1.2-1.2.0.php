<?php

$installer = $this;
$installer->startSetup ();

$table = $installer->getConnection ()->newTable ( $installer->getTable ( 'bongointernational/status' ) )->addColumn ( 'status_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array ('identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true ), 'Status ID' )->addColumn ( 'order_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array ('identity' => false, 'unsigned' => true, 'nullable' => false, 'primary' => false ), 'Order ID' )->addColumn ( 'code', Varien_Db_Ddl_Table::TYPE_VARCHAR, null, array ('nullable' => false ), 'Code' )->addColumn ( 'description', Varien_Db_Ddl_Table::TYPE_TEXT, null, array ('nullable' => false ), 'Description' )->addColumn ( 'notes', Varien_Db_Ddl_Table::TYPE_TEXT, null, array ('nullable' => false ), 'Notes' )->addColumn ( 'created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array ('nullable' => true, 'default' => null ), 'Created At' );
$installer->getConnection ()->createTable ( $table );

$installer->endSetup ();
