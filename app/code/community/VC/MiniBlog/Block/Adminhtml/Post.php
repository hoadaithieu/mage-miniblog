<?php
class VC_MiniBlog_Block_Adminhtml_Post extends Mage_Adminhtml_Block_Widget_Grid_Container {
	public function __construct() {
		$this->_controller = 'adminhtml_post';
		$this->_blockGroup = 'vc_miniblog';
		$this->_headerText = Mage::helper('vc_miniblog')->__('Post Manager');
		$this->_addButtonLabel = Mage::helper('vc_miniblog')->__('Add Post');
		parent::__construct();
		$this->setTemplate('vc_miniblog/post.phtml');
	}
	
    public function isSingleStoreMode()
    {
        if (!Mage::app()->isSingleStoreMode()) {
               return false;
        }
        return true;
    }
	
}
