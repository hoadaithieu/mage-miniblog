<?php
class VC_MiniBlog_Block_Adminhtml_Post_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
	protected function _prepareForm()
	{
		$form = new Varien_Data_Form();
		$this->setForm($form);
		$fieldset = $form->addFieldset('post_form', array('legend'=>Mage::helper('vc_miniblog')->__('Post information')));
		$priceGroup = Mage::registry('post_data');
		
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
		
		$fieldset->addField(
			'category_id',
			'multiselect',
			array(
				 'name'     => 'categories[]',
				 'label'    => Mage::helper('cms')->__('Category'),
				 'title'    => Mage::helper('cms')->__('Category'),
				 'required' => true,
				 'values'   => Mage::getSingleton('vc_miniblog/system_category')->getCategoryValuesForForm(),
				 'style'  => 'height:120px;',
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
		
		$fieldset->addField('enable_comment', 'select', array(
		  'label'     => Mage::helper('vc_miniblog')->__('Enable Comments'),
		  'name'      => 'enable_comment',
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
		
		$fieldset->addField('tags', 'text', array(
		  'label'     => Mage::helper('vc_miniblog')->__('Tags'),
		  'name'      => 'tags',
		));
		
		
		$config = Mage::getSingleton('cms/wysiwyg_config')->getConfig();

		$fieldset->addField(
			'short_content',
			'textarea',
			array(
				 'name'   => 'short_content',
				 'label'  => Mage::helper('vc_miniblog')->__('Short Description'),
				 'title'  => Mage::helper('vc_miniblog')->__('Short Description'),
				 'style'  => 'width:700px; height:100px;',
			)
		);

		
		$fieldset->addField(
			'description',
			'editor',
			array(
				 'name'   => 'content',
				 'label'  => Mage::helper('vc_miniblog')->__('Description'),
				 'title'  => Mage::helper('vc_miniblog')->__('Description'),
				 'style'  => 'width:700px; height:200px;',
				 'config' => $config,
			)
		);
		
		
		
		if ( Mage::registry('post_data') ) {
			$data = Mage::registry('post_data')->getData();
			$data['store_id'] = Mage::registry('store_data');
			$data['category_id'] = Mage::registry('category_data');
			$data['description'] = isset($data['content']) ? $data['content'] : '';
			
			$form->setValues($data);
		}
		return parent::_prepareForm();
	}
}