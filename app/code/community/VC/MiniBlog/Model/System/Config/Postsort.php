<?php
class VC_MiniBlog_Model_System_Config_Postsort extends Varien_Object
{
    public function toOptionArray()
    {
        return array(
            array(
                'value' => 'created_at',
                'label' => Mage::helper('adminhtml')->__('Created At'),
            ),
            array(
                'value' => 'updated_at',
                'label' => Mage::helper('adminhtml')->__('Updated At'),
            ),
			
            array(
                'value' => 'position',
                'label' => Mage::helper('adminhtml')->__('Position'),
            ),
            array(
                'value' => 'poster',
                'label' => Mage::helper('adminhtml')->__('Poster'),
            ),
            array(
                'value' => 'num_comment',
                'label' => Mage::helper('adminhtml')->__('Comment'),
            ),
			
        );
    }
}