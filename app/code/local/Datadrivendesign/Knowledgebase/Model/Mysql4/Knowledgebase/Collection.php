<?php

class Datadrivendesign_Knowledgebase_Model_Mysql4_Knowledgebase_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('knowledgebase/knowledgebase');
    }
}