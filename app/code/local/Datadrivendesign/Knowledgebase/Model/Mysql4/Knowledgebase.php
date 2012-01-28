<?php

class Datadrivendesign_Knowledgebase_Model_Mysql4_Knowledgebase extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the knowledgebase_id refers to the key field in your database table.
        $this->_init('knowledgebase/knowledgebase', 'knowledgebase_id');
    }
}