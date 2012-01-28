<?php

class Datadrivendesign_Knowledgebase_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function getAttribute($attributeCode){
		$code = $attributeCode;
		$product = Mage::getModel('catalog/product');
 
		$attributes = Mage::getResourceModel('eav/entity_attribute_collection')
			->setEntityTypeFilter($product->getResource()->getTypeId())
			->addFieldToFilter('attribute_code', $code) // This can be changed to any attribute code
			->load(false);
		 
		$attribute = $attributes->getFirstItem()->setEntity($product->getResource());
		 
		/* @var $attribute Mage_Eav_Model_Entity_Attribute */
		$attribute = $attribute->getSource()->getAllOptions(false);
		return $attribute;
	}
	
	public function attributeArrayToOptions($attributeCode){
		$code = $attributeCode;
		$attribute = $this->getAttribute($code);
		
		foreach($attribute as $option){
			$options[$option['value']] = $option['label'];
		}
		
		return $options;
	}
}