<?php
class VC_MiniBlog_Block_Adminhtml_Category extends Mage_Adminhtml_Block_Widget_Grid_Container {
	public function __construct() {
		$this->_controller = 'adminhtml_category';
		$this->_blockGroup = 'vc_miniblog';
		$this->_headerText = Mage::helper('vc_miniblog')->__('Category Manager');
		$this->_addButtonLabel = Mage::helper('vc_miniblog')->__('Add Category');
		parent::__construct();
	}
}