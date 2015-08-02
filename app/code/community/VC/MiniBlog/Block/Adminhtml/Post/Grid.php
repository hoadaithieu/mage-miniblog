<?php
class VC_MiniBlog_Block_Adminhtml_Post_Grid extends Mage_Adminhtml_Block_Widget_Grid {
	public function __construct() {
		parent::__construct();
		$this->setId('postGrid');
		$this->setDefaultSort('post_id');
		$this->setDefaultDir('ASC');
		$this->setSaveParametersInSession(true);
	}
	
	/**
	@ method : _prepareCollection
	**/
	
	protected function _prepareCollection() {
		//$this->setSaveParametersInSession(false);
		$collection = Mage::getModel('vc_miniblog/post')->getCollection();
		//$storeId = $this->getParam('store', null);
		$storeId = $this->getRequest()->getParam('store', null);
		if ($storeId > 0) {
			$collection->getSelect()
			->joinInner(
				array('ps' => Mage::getSingleton('core/resource')->getTableName('vc_miniblog/post_store')),
				'ps.post_id = main_table.post_id AND ps.store_id = '. $storeId,
				array('ps.store_id AS store_id')
			);
		}
		
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}
	
	/**
	@ method : _prepareColumns
	**/
	
	protected function _prepareColumns() {
		$this->addColumn('post_id', array(
		  'header'    => Mage::helper('vc_miniblog')->__('ID'),
		  'align'     =>'right',
		  'width'     => '50px',
		  'index'     => 'post_id',
		));
		
		$this->addColumn('title', array(
		  'header'    => Mage::helper('vc_miniblog')->__('Title'),
		  'align'     =>'left',
		  'width'     => '100px',
		  'index'     => 'title',
		));
		
		$this->addColumn('identifier', array(
		  'header'    => Mage::helper('vc_miniblog')->__('Identifier'),
		  'align'     =>'left',
		  'index'     => 'identifier',
		));
		
		$this->addColumn('poster', array(
		  'header'    => Mage::helper('vc_miniblog')->__('Poster'),
		  'align'     =>'left',
		  'index'     => 'poster',
		));
		
				
		$this->addColumn('created_at', array(
		  'header'    => Mage::helper('vc_miniblog')->__('Created At'),
		  'align'     =>'left',
		  'width'     => '120px',
		  'index'     => 'created_at',
		  'type' => 'datetime',
		 'gmtoffset' => true,
		 'default'   => ' -- '
		  
		));
		
		$this->addColumn('updated_at', array(
		  'header'    => Mage::helper('vc_miniblog')->__('Updated At'),
		  'align'     =>'left',
		  'width'     => '120px',
		  'index'     => 'updated_at',
		  'type' => 'datetime',
		 'gmtoffset' => true,
		 'default'   => ' -- '
		  
		));
		
		
		
		$this->addColumn('enable', array(
		  'header'    => Mage::helper('vc_miniblog')->__('Status'),
		  'align'     => 'left',
		  'width'     => '80px',
		  'index'     => 'enable',
		  'type'      => 'options',
		  'options'   => array(
			  1 => 'Enabled',
			  2 => 'Disabled',
		  ),
		));
		
		$this->addColumn('action',
			array(
				'header'    =>  Mage::helper('vc_miniblog')->__('Action'),
				'width'     => '100px',
				'type'      => 'action',
				'getter'    => 'getId',
				'actions'   => array(
					array(
						'caption'   => Mage::helper('vc_miniblog')->__('Change Status'),
						'url'       => array('base'=> '*/*/status'),
						'field'     => 'id'
					),
				
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
		$this->setMassactionIdField('post_id');
		$this->getMassactionBlock()->setFormFieldName('vc_miniblog');
		
		$this->getMassactionBlock()->addItem('delete', array(
			 'label'    => Mage::helper('vc_miniblog')->__('Delete'),
			 'url'      => $this->getUrl('*/*/massDelete'),
			 'confirm'  => Mage::helper('vc_miniblog')->__('Are you sure?')
		));
		
		$statusAr = Mage::getSingleton('vc_miniblog/status')->getOptionArray();
		array_unshift($statusAr, array('label' => '', 'value' =>  ''));
		$this->getMassactionBlock()->addItem('status', array(
			 'label' => Mage::helper('vc_miniblog')->__('Change status'),
			 'url'  => $this->getUrl('*/*/massStatus', array('_current' => true)),
			 'additional' => array(
					'visibility' => array(
						 'name' => 'status',
						 'type' => 'select',
						 'class' => 'required-entry',
						 'label' => Mage::helper('vc_miniblog')->__('Status'),
						 'values' => $statusAr
					 )
			 )
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