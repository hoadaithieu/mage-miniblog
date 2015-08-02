<?php
class VC_MiniBlog_Model_System_Config_Layout extends Varien_Object
{
    protected $_options = null;

    public function toOptionArray()
    {
        if ($this->_options === null) {
            $this->_options = array();
            foreach (Mage::getSingleton('page/config')->getPageLayouts() as $layout) {
                $this->_options[] = array(
                    //'value' => $layout->getTemplate(),
					'value' => $layout->getCode(),
                    'label' => $layout->getLabel(),
                );
            }
        }
        return $this->_options;
    }

}