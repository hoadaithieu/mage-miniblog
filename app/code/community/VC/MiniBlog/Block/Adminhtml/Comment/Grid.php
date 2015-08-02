<?php
class VC_MiniBlog_Block_Adminhtml_Comment_Grid extends Mage_Adminhtml_Block_Widget_Grid {
	public function __construct() {
		parent::__construct();
		$this->setId('commentGrid');
		$this->setDefaultSort('comment_id');
		$this->setDefaultDir('ASC');
		//$this->setSaveParametersInSession(true);
	}
	
	/**
	@ method : _prepareCollection
	**/
	
	protected function _prepareCollection() {
		$collection = Mage::getModel('vc_miniblog/comment')->getCollection();
		$collection->getSelect()
        ->joinLeft(
            array('p' => Mage::getSingleton('core/resource')->getTableName('vc_miniblog/post')),
            'p.post_id = main_table.post_id',
            array('p.title AS post_title')
        );

		$this->setCollection($collection);
		return parent::_prepareCollection();
	}
	
	/**
	@ method : _prepareColumns
	**/
	
	protected function _prepareColumns() {
		$this->addColumn('comment_id', array(
		  'header'    => Mage::helper('vc_miniblog')->__('ID'),
		  'align'     =>'right',
		  'width'     => '50px',
		  'index'     => 'comment_id',
		));
		
		
		$this->addColumn('content', array(
		  'header'    => Mage::helper('vc_miniblog')->__('Comment'),
		  'align'     =>'left',
		  'index'     => 'content',
		));
		
		$this->addColumn('user', array(
		  'header'    => Mage::helper('vc_miniblog')->__('Poster'),
		  'align'     =>'left',
		  'index'     => 'user',
		));
		
		$this->addColumn('email', array(
		  'header'    => Mage::helper('vc_miniblog')->__('Email Address'),
		  'align'     =>'left',
		  'index'     => 'email',
		));
		
		$this->addColumn('post_title', array(
		  'header'    => Mage::helper('vc_miniblog')->__('Post Title'),
		  'align'     => 'left',
		  'index'     => 'post_title',
		));
			
		$this->addColumn('post_link', array(
		  'header'    => Mage::helper('vc_miniblog')->__('Link To Post'),
		  'align'     => 'left',
		  'filter'    => false,
		  'index'     => 'post_link',
		  'renderer' => 'vc_miniblog/adminhtml_column_renderer_postlink'
		));
			
		$this->addColumn('created_at', array(
			'header'    => Mage::helper('vc_miniblog')->__('Created At'),
			'align'     =>'left',
			'width'     => '120px',
			'index'     => 'created_at',
			'type' 		=> 'datetime',
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
			  1 => 'Approved',
			  2 => 'Unapproved',
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
						'caption'   => Mage::helper('vc_miniblog')->__('Approve'),
						'url'       => array('base'=> '*/*/approve'),
						'field'     => 'id'
					),
					array(
						'caption'   => Mage::helper('vc_miniblog')->__('Unapprove'),
						'url'       => array('base'=> '*/*/unapprove'),
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
		$this->setMassactionIdField('comment_id');
		$this->getMassactionBlock()->setFormFieldName('vc_miniblog');
		
		$this->getMassactionBlock()->addItem('delete', array(
			 'label'    => Mage::helper('vc_miniblog')->__('Delete'),
			 'url'      => $this->getUrl('*/*/massDelete'),
			 'confirm'  => Mage::helper('vc_miniblog')->__('Are you sure?')
		));
		
		$this->getMassactionBlock()->addItem('approve', array(
			 'label'    => Mage::helper('vc_miniblog')->__('Approve'),
			 'url'      => $this->getUrl('*/*/massApprove'),
			 'confirm'  => Mage::helper('vc_miniblog')->__('Are you sure?')
		));
	
		$this->getMassactionBlock()->addItem('unapprove', array(
			 'label'    => Mage::helper('vc_miniblog')->__('Unapprove'),
			 'url'      => $this->getUrl('*/*/massUnapprove'),
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