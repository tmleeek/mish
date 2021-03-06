<?php

class Mish_Personallogistic_Adminhtml_PersonallogisticordersController extends Mage_Adminhtml_Controller_action
{

	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('personallogistic/items')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));
		
		return $this;
	}   
 
	public function indexAction() {
		$this->_initAction()
			->renderLayout();
	}

	public function paypluserCostAction()
	{
		$data = $this->getRequest()->getPost();
		$modelrowId            = $data['rowid'];
		$paymentcomment        = $data['orderpaymentcomment'];
		$paymentdispatchamount = $data['dispatch_amount'];

		$calculatedAmount = ((20/100)* $paymentdispatchamount);
		$totalpaymentAmount = ($paymentdispatchamount-$calculatedAmount);

		$updatepluserorderModel = Mage::getModel('personallogistic/personallogisticuserorder')->load($modelrowId);
		$updatepluserorderModel-> setPaymentStatus(1);
		$updatepluserorderModel-> setPaymentComment($paymentcomment);
		$updatepluserorderModel-> setPaymentAmount($totalpaymentAmount);
		$updatepluserorderModel-> save();

		Mage::getSingleton("core/session")->addSuccess("Payment has been done from admin end."); 
		$this->_redirectReferer();
		
	}

	public function editAction() {

		$this->_initAction()
			->renderLayout();
		// $id     = $this->getRequest()->getParam('id');
		// $model  = Mage::getModel('personallogistic/personallogistic')->load($id);

		// if ($model->getId() || $id == 0) {
		// 	$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
		// 	if (!empty($data)) {
		// 		$model->setData($data);
		// 	}

		// 	Mage::register('personallogistic_data', $model);

		// 	$this->loadLayout();
		// 	$this->_setActiveMenu('personallogistic/items');

		// 	$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
		// 	$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));

		// 	$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

		// 	$this->_addContent($this->getLayout()->createBlock('personallogistic/adminhtml_personallogistic_edit'))
		// 		->_addLeft($this->getLayout()->createBlock('personallogistic/adminhtml_personallogistic_edit_tabs'));

		// 	$this->renderLayout();
		// } else {
		// 	Mage::getSingleton('adminhtml/session')->addError(Mage::helper('personallogistic')->__('Item does not exist'));
		// 	$this->_redirect('*/*/');
		// }
	}
 
	public function newAction() {
		$this->_forward('edit');
	}
 
	public function saveAction() {
		if ($data = $this->getRequest()->getPost()) {
			
			if(isset($_FILES['filename']['name']) && $_FILES['filename']['name'] != '') {
				try {	
					/* Starting upload */	
					$uploader = new Varien_File_Uploader('filename');
					
					// Any extention would work
	           		$uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
					$uploader->setAllowRenameFiles(false);
					
					// Set the file upload mode 
					// false -> get the file directly in the specified folder
					// true -> get the file in the product like folders 
					//	(file.jpg will go in something like /media/f/i/file.jpg)
					$uploader->setFilesDispersion(false);
							
					// We set media as the upload dir
					$path = Mage::getBaseDir('media') . DS ;
					$uploader->save($path, $_FILES['filename']['name'] );
					
				} catch (Exception $e) {
		      
		        }
	        
		        //this way the name is saved in DB
	  			$data['filename'] = $_FILES['filename']['name'];
			}
	  			
	  			
			$model = Mage::getModel('personallogistic/personallogistic');		
			$model->setData($data)
				->setId($this->getRequest()->getParam('id'));
			
			try {
				if ($model->getCreatedTime == NULL || $model->getUpdateTime() == NULL) {
					$model->setCreatedTime(now())
						->setUpdateTime(now());
				} else {
					$model->setUpdateTime(now());
				}	
				
				$model->save();
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('personallogistic')->__('Item was successfully saved'));
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
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('personallogistic')->__('Unable to find item to save'));
        $this->_redirect('*/*/');
	}
 
	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$model = Mage::getModel('personallogistic/personallogistic');
				 
				$model->setId($this->getRequest()->getParam('id'))
					->delete();
					 
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
        $personallogisticIds = $this->getRequest()->getParam('personallogistic');
        if(!is_array($personallogisticIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($personallogisticIds as $personallogisticId) {
                    $personallogistic = Mage::getModel('personallogistic/personallogistic')->load($personallogisticId);
                    $personallogistic->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($personallogisticIds)
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
        $personallogisticIds = $this->getRequest()->getParam('personallogistic');
        if(!is_array($personallogisticIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
        } else {
            try {
                foreach ($personallogisticIds as $personallogisticId) {

                    $personallogistic = Mage::getSingleton('personallogistic/personallogistic')
                        ->load($personallogisticId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                        	if($this->getRequest()->getParam('status') == 1){
                        	Mage::dispatchEvent('personallogistic_custom_event_approve_after',array('id'=>$personallogisticIds,'status'=>$this->getRequest()->getParam('status')));
                        }elseif($this->getRequest()->getParam('status') == 2){
                        	Mage::dispatchEvent('personallogistic_custom_event_unapproval_after',array('id'=>$personallogisticIds,'status'=>$this->getRequest()->getParam('status')));
                        }
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($personallogisticIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
  
    public function exportCsvAction()
    {
        $fileName   = 'personallogistic.csv';
        $content    = $this->getLayout()->createBlock('personallogistic/adminhtml_personallogistic_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName   = 'personallogistic.xml';
        $content    = $this->getLayout()->createBlock('personallogistic/adminhtml_personallogistic_grid')
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