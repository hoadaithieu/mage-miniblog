<?php
class VC_MiniBlog_Model_Comment extends Mage_Core_Model_Abstract
{
    public function _construct() {
        parent::_construct();
        $this->_init('vc_miniblog/comment', 'comment_id');
    }
	
}
