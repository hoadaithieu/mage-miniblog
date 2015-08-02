<?php
class VC_MiniBlog_Model_System_Config_Enablemenu extends Varien_Object
{

    protected $_options;

    public function toOptionArray()
    {
        if (!$this->_options) {
            $this->_options[] = array(
                'value' => 1,
                'label' => Mage::helper('cms')->__('Yes, all pages')
            );
            $this->_options[] = array(
                'value' => 2,
                'label' => Mage::helper('cms')->__('Yes, only blog page')
            );
            $this->_options[] = array(
                'value' => 0,
                'label' => Mage::helper('cms')->__('No')
            );
        }
        return $this->_options;
    }

}