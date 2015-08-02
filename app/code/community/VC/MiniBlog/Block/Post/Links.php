<?php   
class VC_MiniBlog_Block_Post_Links extends Mage_Core_Block_Template{ 
	public function addFooterLink() {
		$block = $this->getLayout()->getBlock('footer_links');
		$block->addLink(Mage::getStoreConfig('vc_miniblog/blog/title'),
		Mage::getUrl($this->helper('vc_miniblog')->getBlogUrl(), array('_secure' => true)),
		Mage::getStoreConfig('vc_miniblog/blog/title'));
	}
	
	public function addTopLink() {
		$block = $this->getLayout()->getBlock('top.links');
		$block->addLink(Mage::getStoreConfig('vc_miniblog/blog/title'),
		Mage::getUrl($this->helper('vc_miniblog')->getBlogUrl(), array('_secure' => true)),
		Mage::getStoreConfig('vc_miniblog/blog/title'));
	}
	
}