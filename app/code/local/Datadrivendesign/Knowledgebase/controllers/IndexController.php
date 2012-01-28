<?php
class Datadrivendesign_Knowledgebase_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
		$this->loadLayout();     
		$this->renderLayout();
    }
	
	public function searchAction()
	{
		$query = $this->getRequest()->getParam('q');
		//$doc_type = $this->getRequest()->getParam('t');
		//$page_number = $this->getRequest()->getParam('p');
		//$num_results = 12;
		//$num_start = $page_number*$num_results;
		
		try{
			$model = Mage::getModel('knowledgebase/knowledgebase');	
			$documents = $model->getCollection()->addFieldToFilter('title',array("like"=>'%'.$query.'%'));
				
			$i = 0;
			foreach($documents as $document){
				
				// Convert document_type option id to frontend value
				$doc_typeArray = Mage::helper('knowledgebase')->attributeArrayToOptions('document_type');
				$document_type = $doc_typeArray[$document->getType()];
				
				// Convert manufacturer option id to frontend value
				$manufacturerArray = Mage::helper('knowledgebase')->attributeArrayToOptions('manufacturer');
				$brand = $manufacturerArray[$document->getBrand()];
				
				$response['result'.$i]['id'] = $document->getId();
				$response['result'.$i]['title'] = $document->getTitle();
				$response['result'.$i]['brand'] = $brand;
				$response['result'.$i]['type'] = $document_type;
				$response['result'.$i]['published'] = $document->getPublished();
				$response['result'.$i]['thumbnail_url'] = $document->getThumbnailUrl();
				$response['result'.$i]['doc_id'] = $document->getDocId();
				$response['result'.$i]['access_key'] = $document->getAccessKey();
				$i++;
			}
			
			
		}catch( exception $e){}
		
		// Return response in JSON format to requesting script
		echo json_encode($response);
	}
}