<?php
class VC_MiniBlog_Adminhtml_CategoryController extends Mage_Adminhtml_Controller_Action {
	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('vc_miniblog/items')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Category Manager'), Mage::helper('adminhtml')->__('Category Manager'));
		
		return $this;
	}   
 
 	/**
	@ method : indexAction
	**/
	
	public function indexAction() {
		//Mage::helper('vc_miniblog')->createFolder('ambassador1');	
		$this->_initAction()
			->renderLayout();
	}

	/**
	@ method : editAction
	**/
	
	public function editAction() {
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('vc_miniblog/category')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}
			
			$storeAr = array();
			$collection = Mage::getResourceModel('vc_miniblog/category_store_collection')
			->addFieldToFilter('category_id', $id);
			if ($collection->count() > 0) {
				foreach ($collection as $item) {
					$storeAr[] = $item->getStoreId();
				}
			}

			Mage::register('category_id', $id);
			Mage::register('category_data', $model);
			Mage::register('store_data', $storeAr);

			$this->loadLayout();
			$this->_setActiveMenu('vc_miniblog/items');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Category Manager'), Mage::helper('adminhtml')->__('Category Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Category News'), Mage::helper('adminhtml')->__('Category News'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('vc_miniblog/adminhtml_category_edit'))
				->_addLeft($this->getLayout()->createBlock('vc_miniblog/adminhtml_category_edit_tabs'));
			$this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('vc_miniblog')->__('Category does not exist'));
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

			$model = Mage::getModel('vc_miniblog/category')->load($this->getRequest()->getParam('id'));		
			try {
				
				if ($model->getCreatedAt() == NULL) {
					$model->setCreatedAt(now());
				}
				
				$identifier = Mage::helper('vc_miniblog')->preProcessIdentifier($data['title']);
				if (!$model->validIdentifier($identifier)) throw new Exception(Mage::helper('vc_miniblog')->__('Identifier field is exiting for other item.'));
				
				$model->setTitle($data['title'])
					->setIdentifier($identifier)
					->setSortOrder($data['sort_order'])
					->setMetaKeyword($data['meta_keyword'])
					->setMetaDescription($data['meta_description']);
				
				$model->save();
				
				
				$categoryId = $model->getId();
				if ($categoryId > 0) {
					Mage::getModel('vc_miniblog/category_store')->deleteByCategoryId($categoryId);
					
					if (is_array($data['stores']) && count($data['stores']) > 0) {
						foreach ($data['stores'] as $storeId) {
							$model = Mage::getModel('vc_miniblog/category_store');
							$model->setCategoryId($categoryId)
							->setStoreId($storeId)
							->save();
						}
					}
				}
				
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('vc_miniblog')->__('Category was successfully saved'));
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
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('vc_miniblog')->__('Unable to find Category to save'));
        $this->_redirect('*/*/');
	}
	
	/**
	@ method : deleteAction
	**/
 
	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$model = Mage::getModel('vc_miniblog/category')->load($this->getRequest()->getParam('id'));
				$model->delete(); 
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Category was successfully deleted'));
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
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select Category(s)'));
        } else {
            try {
                foreach ($ppIds as $ppId) {
					$model = Mage::getModel('vc_miniblog/category')->load($ppId);
					$model->delete();
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