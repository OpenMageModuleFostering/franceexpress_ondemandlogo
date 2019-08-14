<?php

$installer = $this;

$installer->startSetup();

$installer->run("
DROP TABLE IF EXISTS {$this->getTable('franceexpress_ondemandlogo')};
DELETE FROM {$this->getTable('core/config_data')} WHERE path like 'franceexpress%';
DELETE FROM {$this->getTable('core/resource')} WHERE code like 'franceexpress_setup';
");

$installer->endSetup();
