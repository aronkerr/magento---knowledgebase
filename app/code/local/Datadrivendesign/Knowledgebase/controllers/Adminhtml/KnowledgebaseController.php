<?php

class Datadrivendesign_Knowledgebase_Adminhtml_KnowledgebaseController extends Mage_Adminhtml_Controller_action
{
	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('knowledgebase/items')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));
		
		return $this;
	}   
 
	public function indexAction() {
		$this->_initAction()
			->renderLayout();
	}

	public function editAction() {
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('knowledgebase/knowledgebase')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register('knowledgebase_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('knowledgebase/items');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('knowledgebase/adminhtml_knowledgebase_edit'))
				->_addLeft($this->getLayout()->createBlock('knowledgebase/adminhtml_knowledgebase_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('knowledgebase')->__('Item does not exist'));
			$this->_redirect('*/*/');
		}
	}
 
	public function newAction() {
		$this->_forward('edit');
	}
 
	public function saveAction() {
		
		$scribd =  Mage::helper('knowledgebase/scribd');
		$model = Mage::getModel('knowledgebase/knowledgebase');	
		
		if ($data = $this->getRequest()->getPost()) {
			
			if(isset($_FILES['filename']['name']) && $_FILES['filename']['name'] != '') {	
				try {
					
					$file = $_FILES['filename']['tmp_name'];
					$doc_type = 'pdf';
					$access = 'private';
					$rev_id = NULL;
					
					// Check if the document already exists. If it does, set doc_id to $doc_id. 
					$doc_id = $model->load($this->getRequest()->getParam('id'))->getDoc_id();
					
					if($doc_id) {					
						$rev_id = $doc_id;
					}
					
					// Upload to scribd. See http://www.scribd.com/developers/api?method_name=docs.upload
					$result = $scribd->upload($file, $doc_type, $access, $rev_id);
					
				} catch (Exception $e) {
			  
				}
				
				// Store returned Scribd file information in $data array() for writing to database.
				$data['doc_id'] = $result['doc_id'];
				$data['doc_type'] = $doc_type;
				$data['access_key'] = $result['access_key'];
				$data['secret_password'] = $result['secret_password'];
			}
			
			$id = $this->getRequest()->getParam('id');
			$model->setData($data)
				->setId($id);
				
			$model->save();
			$model->load($id);
			$doc_id = $model->getDoc_id();
			$result = $scribd->getReadyState($doc_id);
			
			while($result['conversion_status'] == 'PROCESSING'){
				sleep(1);
				$result = $scribd->getReadyState($doc_id);
			}
			
			// If update is TRUE update thumbnail and download link
			if($doc_id || $rev_id != NULL) {
				try {						
					// Get doc_id of request				
					$width = '302';
					$height = '385';
					$doc_type = 'pdf';
					
					// After upload get thumbnail url from scribd. See http://www.scribd.com/developers/api?method_name=thumbnail.get
					$result = $scribd->getThumbnail($doc_id, $width, $height);
					
					// Store returned Scribd file information in database.
					$model->setThumbnail_url($result['thumbnail_url']);
					
					// Get url to download document from Scribd. See http://www.scribd.com/developers/api?method_name=docs.getDownloadUrl				
					$result = $scribd->getDownloadUrl($doc_id, $doc_type);
				
					// Store returned Scribd file information in database.
					$model->setDownload_link($result['download_link']);
						
				} catch (Exception $e) {
			  
				}
			}
			
			$model->save();
				
			try {				
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('knowledgebase')->__('Item was successfully saved'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);
				
				if ($this->getRequest()->getParam('back')) {
					$this->_redirect('*/*/edit', array('id' => $model->getId()));
					return;
				}
				$this->_redirect('*/*/');
				return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('knowledgebase')->__('Unable to find item to save'));
        $this->_redirect('*/*/');
	}
 
	public function deleteAction() {
		
		$scribd =  Mage::helper('knowledgebase/scribd');
		
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$id = $this->getRequest()->getParam('id');
				$object = Mage::getModel('knowledgebase/knowledgebase')->load($id);	
				
				// Get doc_id from object and store in order to send delete request to Scribd.
				$doc_id = $object->getDoc_id();
				
				// Delete doc_id from Scribd. See http://www.scribd.com/developers/api?method_name=docs.delete
				$scribd->delete($doc_id);
				
				// If successfully deleted from Scribd delete reference in Magento database.
				$object->delete();							
				
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}

    public function massDeleteAction() {
		
		$scribd =  Mage::helper('knowledgebase/scribd');
		
        $knowledgebaseIds = $this->getRequest()->getParam('knowledgebase');
        if(!is_array($knowledgebaseIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($knowledgebaseIds as $knowledgebaseId) {
                    $object = Mage::getModel('knowledgebase/knowledgebase')->load($knowledgebaseId);
					
					// Get doc_id from object and store in order to send delete request to Scribd.
					$doc_id = $object->getDoc_id();
					
					// Delete doc_id from Scribd. See http://www.scribd.com/developers/api?method_name=docs.delete
					$scribd->delete($doc_id);
					
					// If successfully deleted from Scribd delete reference in Magento database.
                    $object->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($knowledgebaseIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
	
    public function massStatusAction()
    {
        $knowledgebaseIds = $this->getRequest()->getParam('knowledgebase');
        if(!is_array($knowledgebaseIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($knowledgebaseIds as $knowledgebaseId) {
                    $knowledgebase = Mage::getSingleton('knowledgebase/knowledgebase')
                        ->load($knowledgebaseId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($knowledgebaseIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
  
    public function exportCsvAction()
    {
        $fileName   = 'knowledgebase.csv';
        $content    = $this->getLayout()->createBlock('knowledgebase/adminhtml_knowledgebase_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName   = 'knowledgebase.xml';
        $content    = $this->getLayout()->createBlock('knowledgebase/adminhtml_knowledgebase_grid')
            ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }

    protected function _sendUploadResponse($fileName, $content, $contentType='application/octet-stream')
    {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK','');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename='.$fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }
}