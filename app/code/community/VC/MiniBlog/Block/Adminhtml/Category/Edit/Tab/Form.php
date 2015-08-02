<?php
class VC_MiniBlog_Block_Adminhtml_Category_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
	protected function _prepareForm()
	{
		$form = new Varien_Data_Form();
		$this->setForm($form);
		$fieldset = $form->addFieldset('category_form', array('legend'=>Mage::helper('vc_miniblog')->__('Category information')));
		$priceGroup = Mage::registry('category_data');
		
		
		$fieldset->addField('title', 'text', array(
		  'label'     => Mage::helper('vc_miniblog')->__('Title'),
		  'class'     => 'required-entry',
		  'required'  => true,
		  'name'      => 'title',
		));
		
		/*
		$fieldset->addField('identifier', 'text', array(
		  'label'     => Mage::helper('vc_miniblog')->__('Identifier'),
		  'class'     => 'required-entry',
		  'required'  => true,
		  'name'      => 'identifier',
		));
		*/

		$fieldset->addField('sort_order', 'text', array(
		  'label'     => Mage::helper('vc_miniblog')->__('Sort Order'),
		  'name'      => 'sort_order',
		));
		
        /**
         * Check is single store mode
         */
        if (!Mage::app()->isSingleStoreMode()) {
            $fieldset->addField(
                'store_id',
                'multiselect',
                array(
                     'name'     => 'stores[]',
                     'label'    => Mage::helper('cms')->__('Store View'),
                     'title'    => Mage::helper('cms')->__('Store View'),
                     'required' => true,
                     'values'   => Mage::getSingleton('adminhtml/system_store')
                             ->getStoreValuesForForm(false, true),
                )
            );
        }
		
		if ( Mage::registry('category_data') ) {
			$data = Mage::registry('category_data')->getData();
			$data['store_id'] = Mage::registry('store_data');
			$form->setValues($data);
		}
		return parent::_prepareForm();
	}
}