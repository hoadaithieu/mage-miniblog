<?php
class VC_MiniBlog_Model_System_Config_Listmode extends Varien_Object
{
    public function toOptionArray()
    {
        return array(
            array(
                'value' => 'grid',
                'label' => Mage::helper('adminhtml')->__('Grid'),
            ),
            array(
                'value' => 'list',
                'label' => Mage::helper('adminhtml')->__('List'),
            ),
            array(
                'value' => 'grid-list',
                'label' => Mage::helper('adminhtml')->__('Grid-List'),
            ),
            array(
                'value' => 'list-grid',
                'label' => Mage::helper('adminhtml')->__('List-Grid'),
            ),
			
        );
    }
}