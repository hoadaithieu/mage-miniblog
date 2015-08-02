<?php   
class VC_MiniBlog_Block_Post_List extends Mage_Core_Block_Template{ 
 /**
     * Default toolbar block name
     *
     * @var string
     */
    protected $_defaultToolbarBlock = 'vc_miniblog/post_list_toolbar';

    /**
     * Product Collection
     *
     * @var Mage_Eav_Model_Entity_Collection_Abstract
     */
    protected $_postCollection;

    /**
     * Retrieve loaded category collection
     *
     * @return Mage_Eav_Model_Entity_Collection_Abstract
     */
    protected function _getPostCollection()
    {
        if (is_null($this->_postCollection)) {
           
            $this->_postCollection = Mage::getModel('vc_miniblog/post')->getCollection();
			$this->_postCollection->getSelect()->columns(array('num_comment' => '(SELECT COUNT(*) FROM '.Mage::getSingleton('core/resource')->getTableName('vc_miniblog/comment').' WHERE post_id = main_table.post_id)'));
			$tag = $this->getRequest()->getParam('tag');
			$cat = $this->getRequest()->getParam('cat');
			if (strlen($tag) > 0) {
				$this->_postCollection->addFieldToFilter('tags', array('like' => '%'.$tag.'%'));
			}
			
			if (strlen($cat) > 0) {
				$select = new Zend_Db_Select(Mage::getSingleton('core/resource')->getConnection('read'));
				$select->from(array('pc' => Mage::getSingleton('core/resource')->getTableName('vc_miniblog/post_category')), array('post_id'))
				->joinInner(array('c' => Mage::getSingleton('core/resource')->getTableName('vc_miniblog/category')), 'pc.category_id = c.category_id', array())
				->where('c.identifier = ?', $cat);
				
				$this->_postCollection->addFieldToFilter('post_id', array('in' => new Zend_Db_Expr($select)));
				
			}
			
			$select2 = new Zend_Db_Select(Mage::getSingleton('core/resource')->getConnection('read'));
			$select2->from(array('ps' => Mage::getSingleton('core/resource')->getTableName('vc_miniblog/post_store')), array('post_id'))
			
			->where('ps.store_id = ?', Mage::app()->getStore()->getId())
			->orWhere('ps.store_id = ?', 0);
			
			
			$this->_postCollection->addFieldToFilter('post_id', array('in' => new Zend_Db_Expr($select2)))
			;
			
			
        }
        return $this->_postCollection;
    }

   

    /**
     * Retrieve loaded category collection
     *
     * @return Mage_Eav_Model_Entity_Collection_Abstract
     */
    public function getLoadedPostCollection()
    {
        return $this->_getPostCollection();
    }

    /**
     * Retrieve current view mode
     *
     * @return string
     */
    public function getMode()
    {
        return $this->getChild('toolbar')->getCurrentMode();
    }

    /**
     * Need use as _prepareLayout - but problem in declaring collection from
     * another block (was problem with search result)
     */
    protected function _beforeToHtml()
    {
        $toolbar = $this->getToolbarBlock();

        // called prepare sortable parameters
        $collection = $this->_getPostCollection();

        // use sortable parameters
        if ($orders = $this->getAvailableOrders()) {
            $toolbar->setAvailableOrders($orders);
        }
        if ($sort = $this->getSortBy()) {
            $toolbar->setDefaultOrder($sort);
        }
        if ($dir = $this->getDefaultDirection()) {
            $toolbar->setDefaultDirection($dir);
        }
        if ($modes = $this->getModes()) {
            $toolbar->setModes($modes);
        }

        // set collection to toolbar and apply sort
        $toolbar->setCollection($collection);

        $this->setChild('toolbar', $toolbar);
		
        Mage::dispatchEvent('vc_miniblog_block_post_list_collection', array(
            'collection' => $this->_getPostCollection()
        ));
		
        $this->_getPostCollection()->load();

        return parent::_beforeToHtml();
    }

    /**
     * Retrieve Toolbar block
     *
     * @return Mage_Catalog_Block_Product_List_Toolbar
     */
    public function getToolbarBlock()
    {
        if ($blockName = $this->getToolbarBlockName()) {
            if ($block = $this->getLayout()->getBlock($blockName)) {
                return $block;
            }
        }
        $block = $this->getLayout()->createBlock($this->_defaultToolbarBlock, microtime());
        return $block;
    }


    /**
     * Retrieve list toolbar HTML
     *
     * @return string
     */
    public function getToolbarHtml()
    {
        return $this->getChildHtml('toolbar');
    }

    public function setCollection($collection)
    {
        $this->_postCollection = $collection;
        return $this;
    }

    public function addAttribute($code)
    {
        $this->_getPostCollection()->addAttributeToSelect($code);
        return $this;
    }


    /**
     * Retrieve block cache tags based on product collection
     *
     * @return array
     */
    public function getCacheTags()
    {
        return array_merge(
            parent::getCacheTags(),
            $this->getItemsTags($this->_getPostCollection())
        );
    }
}