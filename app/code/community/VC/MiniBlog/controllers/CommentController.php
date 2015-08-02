<?php
class VC_MiniBlog_CommentController extends Mage_Core_Controller_Front_Action{
    protected function _getSession()
    {
        return Mage::getSingleton('vc_miniblog/session');
    }

	public function postAction() {
		if ($this->getRequest()->isPost()) {
			$comment = $this->getRequest()->getPost('comment');	
			$session = $this->_getSession();
			try {
				
				$errorAr = array();
				if (!isset($comment['name']) || strlen(trim($comment['name'])) == 0) {
					$errorAr[] = $this->__('Invalid name.');
				}
				
				if (!isset($comment['name']) || !Zend_Validate::is($comment['email'], 'EmailAddress')) {
					$errorAr[] = $this->__('Invalid email address.');
				}
				
				if (!isset($comment['content']) || strlen(trim($comment['content'])) == 0) {
					$errorAr[] = $this->__('Invalid content.');
				}
				
				
				if (count($errorAr) > 0) {
					throw new Exception(implode("<br/>", $errorAr));
				}
				$enable = 1;
				$model = Mage::getModel('vc_miniblog/comment');
				$model->setUser($comment['name'])
				->setEmail($comment['email'])
				->setContent($comment['content'])
				->setPostId($comment['post_id'])
				->setCreatedAt(date('Y-m-d H:i:s'))
				->setEnable($enable)
				->save();
				
				$session->addSuccess($this->__('Your comment has posted.'));
			} catch (Exception $e) {
				$session->addError($e->getMessage());
			}
			
			$identifier = Mage::helper('vc_miniblog')->getPostIdentifierFromId($comment['post_id']);
			if ($identifier && strlen($identifier) > 0) {
				$this->_redirectUrl(Mage::getUrl('vc_miniblog/index/postDetail', array('_secure' => true, 'identifier' => $identifier)));
			}
							 
		} else {
			$this->_redirectUrl(Mage::getUrl('vc_miniblog/index/index', array('_secure' => true)));
		}
	}
}