<?php
class VC_MiniBlog_Block_Adminhtml_Category_Grid extends Mage_Adminhtml_Block_Widget_Grid {
	public function __construct() {
		parent::__construct();
		$this->setId('categoryGrid');
		$this->setDefaultSort('category_id');
		$this->setDefaultDir('ASC');
		//$this->setSaveParametersInSession(true);
	}
	
	/**
	@ method : _prepareCollection
	**/
	
	protected function _prepareCollection() {
		$collection = Mage::getModel('vc_miniblog/category')->getCollection();
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}
	
	/**
	@ method : _prepareColumns
	**/
	
	protected function _prepareColumns() {
		$this->addColumn('category_id', array(
		  'header'    => Mage::helper('vc_miniblog')->__('ID'),
		  'align'     =>'right',
		  'width'     => '50px',
		  'index'     => 'category_id',
		));
		
		$this->addColumn('title', array(
		  'header'    => Mage::helper('vc_miniblog')->__('Title'),
		  'align'     =>'left',
		  'index'     => 'title'
		));
		
		$this->addColumn('identifier', array(
		  'header'    => Mage::helper('vc_miniblog')->__('Identifier'),
		  'align'     =>'left',
		  'index'     => 'identifier',
		));
		
		
		$this->addColumn('sort_order', array(
		  'header'    => Mage::helper('vc_miniblog')->__('Sort order'),
		  'align'     =>'left',
		  'width'     => '100px',
		  'index'     => 'sort_order',
		));
				
		
		$this->addColumn('action',
			array(
				'header'    =>  Mage::helper('vc_miniblog')->__('Action'),
				'width'     => '100px',
				'type'      => 'action',
				'getter'    => 'getId',
				'actions'   => array(
					array(
						'caption'   => Mage::helper('vc_miniblog')->__('Edit'),
						'url'       => array('base'=> '*/*/edit'),
						'field'     => 'id'
					),
					array(
						'caption'   => Mage::helper('vc_miniblog')->__('Delete'),
						'url'       => array('base'=> '*/*/delete'),
						'field'     => 'id'
					)
				),
				'filter'    => false,
				'sortable'  => false,
				'index'     => 'stores',
				'is_system' => true,
		));
		
		
		return parent::_prepareColumns();
	}
	
	/**
	@ method : _prepareMassaction
	**/
	
	protected function _prepareMassaction() {
		$this->setMassactionIdField('category_id');
		$this->getMassactionBlock()->setFormFieldName('vc_miniblog');
		
		$this->getMassactionBlock()->addItem('delete', array(
			 'label'    => Mage::helper('vc_miniblog')->__('Delete'),
			 'url'      => $this->getUrl('*/*/massDelete'),
			 'confirm'  => Mage::helper('vc_miniblog')->__('Are you sure?')
		));
		
		return $this;
	}
	
	/**
	@ method : getRowUrl
	**/
	
	public function getRowUrl($row) {
		return $this->getUrl('*/*/edit', array('id' => $row->getId()));
	}
	
}