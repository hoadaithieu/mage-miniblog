<?php
class VC_MiniBlog_Block_Adminhtml_Comment extends Mage_Adminhtml_Block_Widget_Grid_Container {
	public function __construct() {
		$this->_controller = 'adminhtml_comment';
		$this->_blockGroup = 'vc_miniblog';
		$this->_headerText = Mage::helper('vc_miniblog')->__('Comment Manager');
		parent::__construct();
		$this->_removeButton('add');
	}
}