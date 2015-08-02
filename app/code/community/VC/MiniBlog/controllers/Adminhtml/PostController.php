<?php
class VC_MiniBlog_Adminhtml_PostController extends Mage_Adminhtml_Controller_Action {
	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('vc_miniblog/items')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Post Manager'), Mage::helper('adminhtml')->__('Post Manager'));
		
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
		$model  = Mage::getModel('vc_miniblog/post')->load($id);

		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}
			
			$storeIdAr = array();
			$collection = Mage::getModel('vc_miniblog/post_store')->getCollection()->addFieldToFilter('post_id', $model->getId());
			if ($collection->getSize() > 0) {
				foreach ($collection as $item) {
					$storeIdAr[] = $item->getStoreId();
				}
			}
			
			$categoryIdAr = array();
			$collection = Mage::getModel('vc_miniblog/post_category')->getCollection()->addFieldToFilter('post_id', $model->getId());
			if ($collection->getSize() > 0) {
				foreach ($collection as $item) {
					$categoryIdAr[] = $item->getCategoryId();
				}
			}
			
			
			Mage::register('store_data', $storeIdAr);
			Mage::register('category_data', $categoryIdAr);
			Mage::register('post_id', $id);
			Mage::register('post_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('vc_miniblog/items');

			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Post Manager'), Mage::helper('adminhtml')->__('Post Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Post News'), Mage::helper('adminhtml')->__('Post News'));

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('vc_miniblog/adminhtml_post_edit'))
				->_addLeft($this->getLayout()->createBlock('vc_miniblog/adminhtml_post_edit_tabs'));
			$this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('vc_miniblog')->__('Post does not exist'));
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
			$model = Mage::getModel('vc_miniblog/post')->load($this->getRequest()->getParam('id'));		
			try {
			
				$identifier = Mage::helper('vc_miniblog')->preProcessIdentifier($data['title']);
				if (!$model->validIdentifier($identifier)) throw new Exception(Mage::helper('vc_miniblog')->__('Identifier field is exiting for other item.'));
			
				
                $format = Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM);
                if (isset($data['created_at']) && $data['created_at']) {
                    $dateFrom = Mage::app()->getLocale()->date($data['created_at'], $format);
                    $model->setCreatedAt(Mage::getModel('core/date')->gmtDate(null, $dateFrom->getTimestamp()));
                    $model->setUpdatedAt(Mage::getModel('core/date')->gmtDate());
                } else {
                    $model->setCreatedAt(Mage::getModel('core/date')->gmtDate());
                }
				
				$userId = Mage::getSingleton('admin/session')->getUser()->getId();
				$user = Mage::getModel("admin/user")->load($userId);					
				$poster = strlen(trim($data['poster'])) > 0 ? trim($data['poster']): $user->getName();
				$model->setTitle($data['title'])
					->setIdentifier($identifier)
					->setMetaKeyword($data['meta_keyword'])
					->setMetaDescription($data['meta_description'])
					->setShortContent($data['short_content'])
					->setContent($data['content'])
					->setTags($data['tags'])
					->setPoster($poster)
					->setEnableComment(($data['enable_comment'] >= 1 && $data['enable_comment'] <= 2) ? $data['enable_comment']: 2)
					->setEnable(($data['enable'] >= 1 && $data['enable'] <= 2) ? $data['enable']: 2);
				
				$model->save();
				
				$postId = $model->getId();
				if ($postId > 0) {
					Mage::getModel('vc_miniblog/post_store')->deleteByPostId($postId);
					
					if (is_array($data['stores']) && count($data['stores']) > 0) {
						foreach ($data['stores'] as $storeId) {
							$model = Mage::getModel('vc_miniblog/post_store');
							$model->setPostId($postId)
							->setStoreId($storeId)
							->save();
						}
					}
					
					Mage::getModel('vc_miniblog/post_category')->deleteByPostId($postId);
					
					if (is_array($data['categories']) && count($data['categories']) > 0) {
						foreach ($data['categories'] as $categoryId) {
							$model = Mage::getModel('vc_miniblog/post_category');
							$model->setPostId($postId)
							->setCategoryId($categoryId)
							->save();
						}
					}
					
				}
				
				
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('vc_miniblog')->__('Post was successfully saved'));
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
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('vc_miniblog')->__('Unable to find Post to save'));
        $this->_redirect('*/*/');
	}
	
	/**
	@ method : deleteAction
	**/
 
	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$this->_deleteAction($this->getRequest()->getParam('id'));	 
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Post was successfully deleted'));
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
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select Post(s)'));
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
	
	/**
	@ method : _deleteAction
	**/
	
	private function _deleteAction($postId = 0) {
		$model = Mage::getModel('vc_miniblog/post')->load($postId);
		if ($model->getId() > 0) {
			$model->delete();				
		}	
	}
	
	public function statusAction() {
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				//$this->_deleteAction($this->getRequest()->getParam('id'));
				$model = Mage::getModel('vc_miniblog/post')->load($this->getRequest()->getParam('id'));	 
				if ($model->getId() > 0) {
					$model->setEnable(($model->getEnable() == 1? 2: 1))->save();
				}
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Post was successfully changed status.'));
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			}
		}
		$this->_redirect('*/*/');	
	}
	
	/**
	@ method : massStatusAction
	**/
	
    public function massStatusAction()
    {
        $ppIds = $this->getRequest()->getParam('vc_miniblog');
        if(!is_array($ppIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select Post(s)'));
        } else {
            try {
                foreach ($ppIds as $ppId) {
                    $pp = Mage::getSingleton('vc_miniblog/post')
                        ->load($ppId)
                        ->setEnable($this->getRequest()->getParam('status'))
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($ppIds))
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