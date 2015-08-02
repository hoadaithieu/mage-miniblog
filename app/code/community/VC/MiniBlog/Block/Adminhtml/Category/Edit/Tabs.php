<?php
class VC_MiniBlog_Block_Adminhtml_Category_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
	public function __construct()
	{
		parent::__construct();
		$this->setId('category_tabs');
		$this->setDestElementId('edit_form');
		$this->setTitle(Mage::helper('vc_miniblog')->__('Category Information'));
	}
	
	protected function _beforeToHtml()
	{
		$this->addTab('form_section', array(
			'label'     => Mage::helper('vc_miniblog')->__('Category Information'),
			'title'     => Mage::helper('vc_miniblog')->__('Category Information'),
			'content'   => $this->getLayout()->createBlock('vc_miniblog/adminhtml_category_edit_tab_form')->toHtml(),
		));

		$this->addTab('meta_section', array(
			'label'     => Mage::helper('vc_miniblog')->__('Meta Information'),
			'title'     => Mage::helper('vc_miniblog')->__('Meta Information'),
			'content'   => $this->getLayout()->createBlock('vc_miniblog/adminhtml_category_edit_tab_meta')->toHtml(),
		));
		
		return parent::_beforeToHtml();
	}
}