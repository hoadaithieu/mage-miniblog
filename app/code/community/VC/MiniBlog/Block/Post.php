<?php   
class VC_MiniBlog_Block_Post extends Mage_Core_Block_Template{ 
	public function getItem() {
		$model = Mage::registry('post_data');
		if ($model && $model->getId() > 0) {
			return $model;
		}
		return null;
	}
	
	public function checkRequiredLogin() {
		$ss = Mage::getSingleton('customer/session');
		if (!Mage::getStoreConfig('vc_miniblog/comment/require_login_to_comment') || 
		(Mage::getStoreConfig('vc_miniblog/comment/require_login_to_comment') && $ss->isLoggedIn())) {
			return true;
		}
		return false;
	}
}