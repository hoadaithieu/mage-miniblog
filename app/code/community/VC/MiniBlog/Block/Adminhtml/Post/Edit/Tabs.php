<?php
class VC_MiniBlog_Block_Adminhtml_Post_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
	public function __construct()
	{
		parent::__construct();
		$this->setId('post_tabs');
		$this->setDestElementId('edit_form');
		$this->setTitle(Mage::helper('vc_miniblog')->__('Post Information'));
	}
	
	protected function _beforeToHtml()
	{
		$this->addTab('form_section', array(
		  'label'     => Mage::helper('vc_miniblog')->__('Post Information'),
		  'title'     => Mage::helper('vc_miniblog')->__('Post Information'),
		  'content'   => $this->getLayout()->createBlock('vc_miniblog/adminhtml_post_edit_tab_form')->toHtml(),
		));

		$this->addTab('meta_section', array(
		  'label'     => Mage::helper('vc_miniblog')->__('Advanced Information'),
		  'title'     => Mage::helper('vc_miniblog')->__('Advanced Information'),
		  'content'   => $this->getLayout()->createBlock('vc_miniblog/adminhtml_post_edit_tab_meta')->toHtml(),
		));

		
		
		return parent::_beforeToHtml();
	}
}