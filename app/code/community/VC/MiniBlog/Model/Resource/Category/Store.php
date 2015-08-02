<?php
class VC_MiniBlog_Model_Resource_Category_Store extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Resource initialization
     */
    protected function _construct()
    {
        $this->_init('vc_miniblog/category_store', null);
    }
	
    public function deleteByCategoryId(Mage_Core_Model_Abstract $object, $categoryId = 0)
    {
        $this->_beforeDelete($object);
        $this->_getWriteAdapter()->delete(
            $this->getMainTable(),
            $this->_getWriteAdapter()->quoteInto('category_id =?', $categoryId)
        );
        $this->_afterDelete($object);
        return $this;
    }
}
