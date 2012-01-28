<?php
class Datadrivendesign_Knowledgebase_Block_Knowledgebase extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getKnowledgebase()     
     { 
        if (!$this->hasData('knowledgebase')) {
            $this->setData('knowledgebase', Mage::registry('knowledgebase'));
        }
        return $this->getData('knowledgebase');
        
    }
}