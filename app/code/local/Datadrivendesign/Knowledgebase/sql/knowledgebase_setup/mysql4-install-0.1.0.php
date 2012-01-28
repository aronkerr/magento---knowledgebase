<?php

$installer = $this;

$installer->startSetup();

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('knowledgebase')};
CREATE TABLE {$this->getTable('knowledgebase')} (
  `knowledgebase_id` int(11) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `brand` varchar(255) NOT NULL default '',
  `type` varchar(255) NOT NULL default '',
  `doc_type` varchar(255) NOT NULL default '',
  `published` varchar(12),
  `doc_id` varchar(255) NOT NULL default '',
  `access_key` varchar(255) NOT NULL default '',
  `secret_password` varchar(255) NOT NULL default '',
  `thumbnail_url` varchar(255) NOT NULL default '',
  `download_link` varchar(255) NOT NULL default '',
  `status` smallint(6) NOT NULL default '0',
  PRIMARY KEY (`knowledgebase_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ");

$installer->endSetup(); 