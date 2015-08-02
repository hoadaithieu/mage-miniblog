<?php
class VC_MiniBlog_Model_System_Category extends Varien_Object
{
    public function getCategoryValuesForForm()
    {
        $options = array();
		
		$collection = Mage::getModel('vc_miniblog/category')->getCollection();
		if ($collection->getSize() > 0) {
			foreach ($collection as $item) {
				$options[] = array(
					'label' => $item->getTitle(),
					'value' => $item->getId()
				);				
			}
		}
		
		if (count($options) == 0) {
            $options[] = array(
                'label' => '',
                'value' => ''
            );
		}

        return $options;
    }
}
