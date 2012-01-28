<?php
class Datadrivendesign_Knowledgebase_Block_Adminhtml_Knowledgebase extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_knowledgebase';
    $this->_blockGroup = 'knowledgebase';
    $this->_headerText = Mage::helper('knowledgebase')->__('Item Manager');
    $this->_addButtonLabel = Mage::helper('knowledgebase')->__('Add Item');
    parent::__construct();
  }
}