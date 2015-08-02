<?php
class VC_MiniBlog_Block_Adminhtml_Comment_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
	protected function _prepareForm()
	{
		$form = new Varien_Data_Form();
		$this->setForm($form);
		$fieldset = $form->addFieldset('comment_form', array('legend'=>Mage::helper('vc_miniblog')->__('Comment information')));
		$priceGroup = Mage::registry('comment_data');
		
		$fieldset->addField('user', 'text', array(
		  'label'     => Mage::helper('vc_miniblog')->__('User'),
		  'class'     => 'required-entry',
		  'required'  => true,
		  'name'      => 'user',
		));
		
		$fieldset->addField('email', 'text', array(
		  'label'     => Mage::helper('vc_miniblog')->__('Email Address'),
		  'class'     => 'required-entry',
		  'required'  => true,
		  'name'      => 'email',
		));

		$fieldset->addField(
			'content',
			'editor',
			array(
				 'name'   => 'content',
				 'label'  => Mage::helper('vc_miniblog')->__('Comment'),
				 'title'  => Mage::helper('vc_miniblog')->__('Comment'),
				 'style'  => 'width:700px; height:400px;',
			)
		);
		
		$fieldset->addField('enable', 'select', array(
		  'label'     => Mage::helper('vc_miniblog')->__('Status'),
		  'name'      => 'enable',
		  'values'    => array(
			  array(
				  'value'     => 1,
				  'label'     => Mage::helper('vc_miniblog')->__('Enabled'),
			  ),
			  array(
				  'value'     => 2,
				  'label'     => Mage::helper('vc_miniblog')->__('Disabled'),
			  ),
		  ),
		));
		
		
		if ( Mage::registry('comment_data') ) {
			$form->setValues(Mage::registry('comment_data')->getData());
		}
		return parent::_prepareForm();
	}
}