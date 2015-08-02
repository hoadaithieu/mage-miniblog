<?php
class VC_MiniBlog_Block_Adminhtml_Category_Edit_Tab_Meta extends Mage_Adminhtml_Block_Widget_Form
{
	protected function _prepareForm()
	{
		$form = new Varien_Data_Form();
		$this->setForm($form);
		$fieldset = $form->addFieldset('category_form', array('legend'=>Mage::helper('vc_miniblog')->__('Meta information')));
		$priceGroup = Mage::registry('category_data');
		
	
		
		
		$fieldset->addField(
			'meta_keyword',
			'textarea',
			array(
				 'name'   => 'meta_keyword',
				 'label'  => Mage::helper('vc_miniblog')->__('Meta Keyword'),
				 'title'  => Mage::helper('vc_miniblog')->__('Meta Keyword'),
				 'style'  => 'width:700px; height:100px;',
			)
		);
		
		$fieldset->addField(
			'meta_description',
			'textarea',
			array(
				 'name'   => 'meta_description',
				 'label'  => Mage::helper('vc_miniblog')->__('Meta Description'),
				 'title'  => Mage::helper('vc_miniblog')->__('Meta Description'),
				 'style'  => 'width:700px; height:100px;',
			)
		);
		

		
		if ( Mage::registry('category_data') ) {
			$form->setValues(Mage::registry('category_data')->getData());
		}
		return parent::_prepareForm();
	}
}