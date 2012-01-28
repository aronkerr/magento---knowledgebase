<?php

class Datadrivendesign_Knowledgebase_Block_Adminhtml_Knowledgebase_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('knowledgebase_form', array('legend'=>Mage::helper('knowledgebase')->__('Item information')));
     
      $fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('knowledgebase')->__('Title'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'title',
      ));
	  
	  $fieldset->addField('brand', 'select', array(
          'label'     => Mage::helper('knowledgebase')->__('Manufacturer'),
          'name'      => 'brand',
          'values'    => Mage::helper('knowledgebase')->getAttribute('manufacturer'),
		  'required'  => true,
      ));
	  
	  $fieldset->addField('type', 'select', array(
          'label'     => Mage::helper('knowledgebase')->__('Document Type'),
          'name'      => 'type',
          'values'    => Mage::helper('knowledgebase')->getAttribute('document_type'),
		  'required'  => true,
      ));
	  
	  $fieldset->addField('published', 'date', array(
          'label'     => Mage::helper('knowledgebase')->__('Published Date'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'published',
		  'image'	  => $this->getSkinUrl('images/grid-cal.gif'),
		  'input_format' => Varien_Date::DATE_INTERNAL_FORMAT,
		  'format'    => Varien_Date::DATE_INTERNAL_FORMAT,
		  'value'	  => 'published',
      ));

      $fieldset->addField('filename', 'file', array(
          'label'     => Mage::helper('knowledgebase')->__('File'),
          'required'  => false,
          'name'      => 'filename',
	  ));
		
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('knowledgebase')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('knowledgebase')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('knowledgebase')->__('Disabled'),
              ),
          ),
      ));
	  
	  $fieldset->addField('doc_id', 'text', array(
          'label'     => Mage::helper('knowledgebase')->__('Doc ID'),
          'class'     => 'required-entry',
          'name'      => 'doc_id',
		  'disabled'  => true,
		  'readonly'  => true,
      ));
		  
	  $fieldset->addField('access_key', 'text', array(
          'label'     => Mage::helper('knowledgebase')->__('Access Key'),
          'class'     => 'required-entry',
          'name'      => 'access_key',
		  'disabled'  => true,
		  'readonly'  => true,
      ));
	  
	  $fieldset->addField('secret_password', 'text', array(
          'label'     => Mage::helper('knowledgebase')->__('Secret Password'),
          'class'     => 'required-entry',
          'name'      => 'secret_password',
		  'disabled'  => true,
		  'readonly'  => true,
      ));
	  
	  $fieldset->addField('download_link', 'text', array(
		  'label'     => Mage::helper('knowledgebase')->__('Download URL'),
		  'name'      => 'download_link',
		  'disabled'  => true,
		  'readonly'  => true,
      ));
	  
	  $fieldset->addField('thumbnail_url', 'thumbnail', array(
		  'label'     => Mage::helper('knowledgebase')->__('Thumbnail (click to download document)'),
		  'name'      => 'thumbnail_url',
		  'style'	  => 'display:none;',
      ));	 
     
      if ( Mage::getSingleton('adminhtml/session')->getKnowledgebaseData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getKnowledgebaseData());
          Mage::getSingleton('adminhtml/session')->setKnowledgebaseData(null);
      } elseif ( Mage::registry('knowledgebase_data') ) {
          $form->setValues(Mage::registry('knowledgebase_data')->getData());
      }
      return parent::_prepareForm();
  }
}