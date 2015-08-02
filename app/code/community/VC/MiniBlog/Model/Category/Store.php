<?php
class VC_MiniBlog_Model_Category_Store extends Mage_Core_Model_Abstract
{
    public function _construct() {
        parent::_construct();
        $this->_init('vc_miniblog/category_store', null);
    }
	
    public function deleteByCategoryId($categoryId = 0)
    {
        $this->_getResource()->beginTransaction();
        try {
            $this->_beforeDelete();
            $this->_getResource()->deleteByCategoryId($this, $categoryId);
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
