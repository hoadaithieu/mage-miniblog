<?php
class VC_MiniBlog_Model_Post_Store extends Mage_Core_Model_Abstract
{
    public function _construct() {
        parent::_construct();
        $this->_init('vc_miniblog/post_store', null);
    }
	
    public function deleteByPostId($postId = 0)
    {
        $this->_getResource()->beginTransaction();
        try {
            $this->_beforeDelete();
            $this->_getResource()->deleteByPostId($this, $postId);
            $this->_afterDelete();

            $this->_getResource()->commit();
            $this->_afterDeleteCommit();
        }
        catch (Exception $e){
            $this->_getResource()->rollBack();
            throw $e;
        }
        return $this;
    }
	
}
