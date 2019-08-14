<?php

$installer = $this;
$installer->startSetup ();

$core_config = Mage::getModel ( 'core/config' );
$core_config->saveConfig ( 'currency/import/enabled', '1', 'default', 0 );
$core_config->saveConfig ( 'currency/import/service', 'bongo_currency_converter', 'default', 0 );
$core_config->saveConfig ( 'currency/import/frequency', 'D', 'default', 0 );

$installer->endSetup ();
