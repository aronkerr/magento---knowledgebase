<?php

class Datadrivendesign_Knowledgebase_Block_Adminhtml_Knowledgebase_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'knowledgebase';
        $this->_controller = 'adminhtml_knowledgebase';
        
        $this->_updateButton('save', 'label', Mage::helper('knowledgebase')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('knowledgebase')->__('Delete Item'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('knowledgebase_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'knowledgebase_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'knowledgebase_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('knowledgebase_data') && Mage::registry('knowledgebase_data')->getId() ) {
            return Mage::helper('knowledgebase')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('knowledgebase_data')->getTitle()));
        } else {
            return Mage::helper('knowledgebase')->__('Add Item');
        }
    }
}