<?php
class VC_MiniBlog_Model_Category extends Mage_Core_Model_Abstract
{
    public function _construct() {
        parent::_construct();
        $this->_init('vc_miniblog/category', 'category_id');
    }
	
	public function validIdentifier($str = '') {
		return $this->_getResource()->validIdentifier($this, $str, $this->getId());
	}
	
}
