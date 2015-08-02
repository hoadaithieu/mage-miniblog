<?php
class VC_MiniBlog_Adminhtml_CommentController extends Mage_Adminhtml_Controller_Action {
	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('vc_miniblog/items')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Comment Manager'), Mage::helper('adminhtml')->__('Comment Manager'));
		
		return $this;
	}   
 
 	/**
	@ method : indexAction
	**/
	
	public function indexAction() {
		$this->_initAction()
			->renderLayout();
	}

	/**
	@ method : editAction
	**/
	
	public function editAction() {
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('vc_miniblog/comment')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register('comment_id', $id);
			Mage::register('comment_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('vc_miniblog/items');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Comment Manager'), Mage::helper('adminhtml')->__('Comment Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Comment News'), Mage::helper('adminhtml')->__('Comment News'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('vc_miniblog/adminhtml_comment_edit'))
				->_addLeft($this->getLayout()->createBlock('vc_miniblog/adminhtml_comment_edit_tabs'));
			$this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('vc_miniblog')->__('Comment does not exist'));
			$this->_redirect('*/*/');
		}
	}
 
 	/**
	@ method : newAction
	**/
	
	public function newAction() {
		$this->_forward('edit');
	}
	
 
 	/**
	@ method : saveAction
	**/
	
	public function saveAction() {
		if ($data = $this->getRequest()->getPost()) {

			$model = Mage::getModel('vc_miniblog/comment')->load($this->getRequest()->getParam('id'));		
			try {
				
				if ($model->getCreatedAt() == NULL) {
					$model->getCreatedAt(now());
				} 
				
				$model->setUser($data['user'])
				->setEmail($data['email'])
				->setContent($data['content'])
				->setEnable(($data['enable'] >= 1 && $data['enable'] <= 2) ? $data['enable']: 2);
				
				
				$model->save();

				
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('vc_miniblog')->__('Comment was successfully saved'));
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
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('vc_miniblog')->__('Unable to find Comment to save'));
        $this->_redirect('*/*/');
	}
	
	/**
	@ method : deleteAction
	**/
 
	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$this->_deleteAction($this->getRequest()->getParam('id'));	 
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Comment was successfully deleted'));
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}

	/**
	@ method : massDeleteAction
	**/
	
    public function massDeleteAction() {
        $ppIds = $this->getRequest()->getParam('vc_miniblog');
        if(!is_array($ppIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select Comment(s)'));
        } else {
            try {
                foreach ($ppIds as $ppId) {
					$this->_deleteAction($ppId);
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__(
                        'Total of %d record(s) were successfully deleted', count($ppIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
	
	public function approveAction() {
		$this->_statusAction(1);
	}
	
	public function unapproveAction() {
		$this->_statusAction(2);
	}
	
	private function _statusAction($val = 0) {
		$id = $this->getRequest()->getParam('id');	
		$pp = Mage::getSingleton('vc_miniblog/comment')
			->load($id)
			->setEnable($val)
			->save();
		$this->_getSession()->addSuccess(
			$this->__('Comment was successfully '.($val == 1? 'approved' : 'unapproved').'.')
		);
			
		$this->_redirect('*/*/index');
	}
	
	/**
	@ method : _deleteAction
	**/
	
	private function _deleteAction($commentId = 0) {
		$model = Mage::getModel('vc_miniblog/comment')->load($commentId);
		if ($model->getId() > 0) {
			$model->delete();				
		}	
	}
	
	/**
	@ method : massStatusAction
	**/
	
	public function massApproveAction() {
		$this->_massStatusAction(1);
	}
	
	public function massUnapproveAction() {
		$this->_massStatusAction(2);
	}
	
	private function _massStatusAction($val = 0) {
        $ppIds = $this->getRequest()->getParam('vc_miniblog');
        if(!is_array($ppIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select Comment(s)'));
        } else {
            try {
                foreach ($ppIds as $ppId) {
                    $pp = Mage::getSingleton('vc_miniblog/comment')
                        ->load($ppId)
                        ->setEnable($val)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated.', count($ppIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
	
	}
	

	/**
	@ method : _sendUploadResponse
	**/
	
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