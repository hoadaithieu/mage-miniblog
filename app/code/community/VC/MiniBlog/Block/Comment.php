<?php   
class VC_MiniBlog_Block_Comment extends Mage_Core_Block_Template{ 
	public function getComments() {
		$model = Mage::registry('post_data');
		if ($model && $model->getId() > 0) {
			$collection = Mage::getModel('vc_miniblog/comment')->getCollection()
			->addFieldToFilter('post_id', $model->getId())
			;
			if (!Mage::getStoreConfig('vc_miniblog/comment/auto_approve_comment')) {
				$collection->addFieldToFilter('enable', array('eq' => 1));
			}
			return $collection;
		}
		return null;
	}
	
	public function getPostId() {
		$model = Mage::registry('post_data');
		if ($model && $model->getId() > 0) {
			return $model->getId();
		}
		return 0;
	}
	
	public function getPostCommentUrl() {
		return $this->helper('vc_miniblog')->getPostCommentUrl();
	}
}