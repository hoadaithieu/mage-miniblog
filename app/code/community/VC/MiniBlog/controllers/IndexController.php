<?php
class VC_MiniBlog_IndexController extends Mage_Core_Controller_Front_Action{
	/**
	@ method : indexAction
	*/
    protected function _getSession()
    {
        return Mage::getSingleton('vc_miniblog/session');
    }
	
    public function indexAction() {
		if (Mage::getStoreConfig('vc_miniblog/blog/enable')) {
			$this->loadLayout();
			$head = $this->getLayout()->getBlock('head');
			$head->setTitle(Mage::getStoreConfig('vc_miniblog/blog/title'));
			$head->setDescription(Mage::getStoreConfig('vc_miniblog/blog/meta_description'));
			$head->setKeywords(Mage::getStoreConfig('vc_miniblog/blog/meta_keyword'));
			
			$this->getLayout()->helper('page/layout')->applyTemplate(Mage::getStoreConfig('vc_miniblog/blog/page_layout'));
			if (($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')) && Mage::getStoreConfig('vc_miniblog/blog/breakcrumbs')) {
				$breadcrumbs->addCrumb('home', array(
					'label'=>Mage::helper('cms')->__('Home'),
					'title'=>Mage::helper('cms')->__('Go to Home Page'),
					'link'=>Mage::getBaseUrl()
				))->addCrumb('miniblog', array(
					'label'=>Mage::helper('cms')->__(Mage::getStoreConfig('vc_miniblog/blog/title'))
				));
			}
			
			$this->renderLayout(); 
		
		} else {
			$session->addError($this->__('Please enable Miniblog extension.'));
			$this->_redirect(Mage::getUrl());
		}
    }
	
	public function postDetailAction() {
		if (Mage::getStoreConfig('vc_miniblog/blog/enable')) {
			$this->loadLayout();
			$identifier = $this->getRequest()->getParam('identifier', '');
			$this->getLayout()->helper('page/layout')->applyTemplate(Mage::getStoreConfig('vc_miniblog/blog/page_layout'));
			if (strlen($identifier) > 0) {
				$model = Mage::getModel('vc_miniblog/post')->load(trim($identifier),'identifier');
				$session = $this->_getSession();
				if (!$model->getId()) {
					$session->addError($e->getMessage());
					$this->_redirectUrl(Mage::getUrl('vc_miniblog/index/index', array('_secure' => true)));
				} else {
					if (($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')) && Mage::getStoreConfig('vc_miniblog/blog/breakcrumbs')) {
						$breadcrumbs->addCrumb('home', array(
							'label'=>Mage::helper('cms')->__('Home'),
							'title'=>Mage::helper('cms')->__('Go to Home Page'),
							'link'=>Mage::getBaseUrl()
						))->addCrumb('miniblog', array(
							'label'=>Mage::helper('cms')->__(Mage::getStoreConfig('vc_miniblog/blog/title')),
							'title'=>Mage::helper('cms')->__('Go to Blog Page'),
							'link'=>Mage::getUrl(Mage::helper('vc_miniblog')->getBlogUrl())						
						))->addCrumb('post', array(
							'label' => $model->getTitle()
						));
					}
					$cat = $this->getRequest()->getParam('cat');
					$head = $this->getLayout()->getBlock('head');
					$head->setTitle(Mage::getStoreConfig('vc_miniblog/blog/title'));
					if (strlen(trim($cat)) > 0) {
						$catModel = Mage::getModel('vc_miniblog/category')->load(trim($cat), 'identifier');
						if ($catModel) {
							$head->setDescription(strlen($catModel->getMetaDescription()) > 0 ? $catModel->getMetaDescription() : Mage::getStoreConfig('vc_miniblog/blog/meta_description'));
							$head->setKeywords(strlen($catModel->getMetaKeyword()) > 0 ? $catModel->getMetaKeyword(): Mage::getStoreConfig('vc_miniblog/blog/meta_keyword'));				
						
						}
					} else {
						$head->setDescription(strlen($model->getMetaDescription()) > 0 ? $model->getMetaDescription() : Mage::getStoreConfig('vc_miniblog/blog/meta_description'));
						$head->setKeywords(strlen($model->getMetaKeyword()) > 0 ? $model->getMetaKeyword(): Mage::getStoreConfig('vc_miniblog/blog/meta_keyword'));				
					}
				
					Mage::register('post_data', $model);
				}
			}
			
			//Mage::register('post_id', $id);
			
			$this->renderLayout(); 
		} else {
			$session->addError($this->__('Please enable Miniblog extension.'));
			$this->_redirect(Mage::getUrl());
		}			
	}
}