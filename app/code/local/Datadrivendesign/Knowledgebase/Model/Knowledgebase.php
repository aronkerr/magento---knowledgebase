<?php

class Datadrivendesign_Knowledgebase_Model_Knowledgebase extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('knowledgebase/knowledgebase');
    }
}