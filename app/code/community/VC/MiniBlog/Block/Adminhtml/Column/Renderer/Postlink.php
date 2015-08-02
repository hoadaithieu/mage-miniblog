<?php
class VC_MiniBlog_Block_Adminhtml_Column_Renderer_Postlink extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function _getValue(Varien_Object $row)
    {
		$value = '<a href="'.$this->getUrl('*/adminhtml_post/edit', array('_current' => true, 'id' => $row->getPostId())).'">View</a>';
		return $value;
    }
}
