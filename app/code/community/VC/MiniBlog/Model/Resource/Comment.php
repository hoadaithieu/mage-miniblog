<?php
class VC_MiniBlog_Model_Resource_Comment extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Resource initialization
     */
    protected function _construct()
    {
        $this->_init('vc_miniblog/comment', 'comment_id');
    }
}
