<?php
class VC_MiniBlog_Helper_Data extends Mage_Core_Helper_Abstract {
	public function preProcessIdentifier($str = '') {
		return preg_replace('#([^(a-z,A-Z,0-9,\-)*])#', '', str_replace(' ', '-', strtolower($str)));
	}
	
	public function convertDate($date = '') {
		 $dateFormat = Mage::app()->getLocale()->getDateTimeFormat(Mage::getStoreConfig('vc_miniblog/blog/date_format'));
		 return Mage::app()->getLocale()->date($date, $dateFormat);
	}
	
	public function getBlogUrl() {
		//return 'vc_miniblog/index/index';
		return Mage::getStoreConfig('vc_miniblog/blog/router');
	}
	
	public function parseTags($tags = '', $limit = 0) {
		$ar = explode(',', $tags);
		$rs = array();
		
		//$urlModel = Mage::getModel('core/url');
		//$urlModel->setAlias($this->getBlogUrl(), 'vc_miniblog/index/index');
		//$urlModel->setAlias(Mage_Core_Model_Url_Rewrite::REWRITE_REQUEST_PATH_ALIAS, $this->getBlogUrl());
		//Mage::app()->getRequest()->setAlias(Mage_Core_Model_Url_Rewrite::REWRITE_REQUEST_PATH_ALIAS, $this->getBlogUrl());
		
		if (is_array($ar) && count($ar) > 0) {
			$j = 0;
			foreach ($ar as $i) {
				if (!isset($rs[trim($i)]) && ($limit ==0 || $limit > 0 && $j < $limit )) {
					$j++;
					$this->_getRequest()->setAlias(Mage_Core_Model_Url_Rewrite::REWRITE_REQUEST_PATH_ALIAS, $this->getBlogUrl().'/tag/'.trim($i));
					$rs[trim($i)] = '<a href="'.$this->_getUrl($this->getBlogUrl(), 
						array('_secure' => true, 
						'_use_rewrite' => true,
						'tag' => trim($i))).'">'.trim($i).'</a>';
					
				}
			}
		}
		return $rs;
	}
	
	public function parseCategoryByPostId($postId = 0) {
		$rs = array();
		$collection = Mage::getModel('vc_miniblog/category')->getCollection();
		$collection->getSelect()->joinInner(array('pc' => Mage::getSingleton('core/resource')->getTableName('vc_miniblog/post_category')),
            'pc.category_id = main_table.category_id AND pc.post_id = '.$postId);
			
		if ($collection->getSize() > 0) {
			foreach ($collection as $item) {
				//$rs[$item->getId()] ='<a href="'.$this->_getUrl($this->getBlogUrl(), array('_secure' => true, 'cat' => trim($item->getIdentifier()))).'">'. trim($item->getIdentifier()).'</a>';
				
				$this->_getRequest()->setAlias(Mage_Core_Model_Url_Rewrite::REWRITE_REQUEST_PATH_ALIAS, $this->getBlogUrl().'/cat/'.trim($item->getIdentifier()));
				$rs[$item->getId()] ='<a href="'.$this->_getUrl($this->getBlogUrl(), 
					array('_secure' => true, 
					'_use_rewrite' => true,
					'cat' => trim($item->getIdentifier()))).'">'. trim($item->getIdentifier()).'</a>';					
				
			}
		}
		
		return $rs;	
	}
	
	public function getPostIdentifierFromId($id = 0) {
		$model = Mage::getModel('vc_miniblog/post')->load($id);
		if ($model && $model->getId() > 0) {
			return $model->getIdentifier();
		}
		return null;
	}
	
    public function getPostCommentUrl()
    {
        return $this->_getUrl('vc_miniblog/comment/post',array('_secure' => true));
    }
	
    public function getPostDetailUrl($identifier = '')
    {
        //return $this->_getUrl('vc_miniblog/index/postDetail/',array('_secure' => true, 'identifier' => $identifier));
		
		$this->_getRequest()->setAlias(Mage_Core_Model_Url_Rewrite::REWRITE_REQUEST_PATH_ALIAS, $this->getBlogUrl().'/'.trim($identifier));
		return $this->_getUrl($this->getBlogUrl(), 
			array('_secure' => true, 
			'_use_rewrite' => true,
			'identifier' => trim($identifier)));					
		
    }

    public function getCatDetailUrl($identifier = '')
    {
        //return $this->_getUrl($this->getBlogUrl(),array('_secure' => true, 'cat' => $identifier));
		$this->_getRequest()->setAlias(Mage_Core_Model_Url_Rewrite::REWRITE_REQUEST_PATH_ALIAS, $this->getBlogUrl().'/cat/'.trim($identifier));
		return $this->_getUrl($this->getBlogUrl(), 
			array('_secure' => true, 
			'_use_rewrite' => true,
			'cat' => trim($identifier)));					
		
    }
	
	
}