<?php
class VC_MiniBlog_Block_Adminhtml_Post_Edit_Tab_Meta extends Mage_Adminhtml_Block_Widget_Form
{
	protected function _prepareForm()
	{
		$form = new Varien_Data_Form();
		$this->setForm($form);
		$fieldset = $form->addFieldset('post_form', array('legend'=>Mage::helper('vc_miniblog')->__('Meta information')));
	
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
		
		$fieldset = $form->addFieldset('advance_form', array('legend'=>Mage::helper('vc_miniblog')->__('Advanced Post Information')));
	
		$fieldset->addField(
			'poster',
			'text',
			array(
				 'name'   => 'poster',
				 'label'  => Mage::helper('vc_miniblog')->__('Poster'),
				 'title'  => Mage::helper('vc_miniblog')->__('Poster'),
			)
		);
        $dateFormat = Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM);
		
        $fieldset->addField('created_at', 'date', array(
            'name'      => 'created_at',
            'label'     => Mage::helper('cms')->__('Created At'),
            'image'     => $this->getSkinUrl('images/grid-cal.gif'),
            'format'    => $dateFormat,
			'time'   => true,
            //'disabled'  => $isElementDisabled,
            'class'     => 'validate-date validate-date-range date-range-created-at'
        ));
		
		
		if ( Mage::registry('post_data') ) {
			$data = Mage::registry('post_data')->getData();
			$form->setValues($data);
            if (isset($data['created_at'])) {
                $form->getElement('created_at')->setValue(
                    Mage::app()->getLocale()->date(
                        $data['created_at'], Varien_Date::DATETIME_INTERNAL_FORMAT
                    )
                );
            }
		}
		return parent::_prepareForm();
	}
}