<?php
class VC_MiniBlog_Model_Resource_Post_Store extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Resource initialization
     */
    protected function _construct()
    {
        $this->_init('vc_miniblog/post_store', null);
    }
	
    public function deleteByPostId(Mage_Core_Model_Abstract $object, $postId = 0)
    {
        $this->_beforeDelete($object);
        $this->_getWriteAdapter()->delete(
            $this->getMainTable(),
            $this->_getWriteAdapter()->quoteInto('post_id =?', $postId)
        );
        $this->_afterDelete($object);
        return $this;
    }
	
}
