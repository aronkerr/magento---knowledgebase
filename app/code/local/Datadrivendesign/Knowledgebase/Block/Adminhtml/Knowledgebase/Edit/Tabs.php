<?php

class Datadrivendesign_Knowledgebase_Block_Adminhtml_Knowledgebase_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('knowledgebase_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('knowledgebase')->__('Item Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('knowledgebase')->__('Item Information'),
          'title'     => Mage::helper('knowledgebase')->__('Item Information'),
          'content'   => $this->getLayout()->createBlock('knowledgebase/adminhtml_knowledgebase_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}