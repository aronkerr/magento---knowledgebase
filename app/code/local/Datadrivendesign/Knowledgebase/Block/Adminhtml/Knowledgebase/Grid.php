<?php

class Datadrivendesign_Knowledgebase_Block_Adminhtml_Knowledgebase_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('knowledgebaseGrid');
      $this->setDefaultSort('knowledgebase_id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('knowledgebase/knowledgebase')->getCollection();
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $this->addColumn('knowledgebase_id', array(
          'header'    => Mage::helper('knowledgebase')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'knowledgebase_id',
      ));

      $this->addColumn('title', array(
          'header'    => Mage::helper('knowledgebase')->__('Title'),
          'align'     =>'left',
          'index'     => 'title',
      ));
	  
	  $this->addColumn('brand', array(
          'header'    => Mage::helper('knowledgebase')->__('Manufacturer'),
          'align'     => 'left',
          'width'     => '80px',
          'index'     => 'brand',
          'type'      => 'options',
          'options'   => Mage::helper('knowledgebase')->attributeArrayToOptions('manufacturer'),
      ));
	  
	  $this->addColumn('type', array(
          'header'    => Mage::helper('knowledgebase')->__('Document Type'),
          'align'     => 'left',
          'width'     => '80px',
          'index'     => 'type',
          'type'      => 'options',
          'options'   => Mage::helper('knowledgebase')->attributeArrayToOptions('document_type'),
      ));
	  
	  $this->addColumn('published', array(
          'header'    => Mage::helper('knowledgebase')->__('Published'),
          'align'     =>'left',
		  'width'     => '100px',
          'index'     => 'published',
      ));

      $this->addColumn('status', array(
          'header'    => Mage::helper('knowledgebase')->__('Status'),
          'align'     => 'left',
          'width'     => '80px',
          'index'     => 'status',
          'type'      => 'options',
          'options'   => array(
              1 => 'Enabled',
              2 => 'Disabled',
          ),
      ));
	  
        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('knowledgebase')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('knowledgebase')->__('Edit'),
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));
		
		$this->addExportType('*/*/exportCsv', Mage::helper('knowledgebase')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('knowledgebase')->__('XML'));
	  
      return parent::_prepareColumns();
  }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('knowledgebase_id');
        $this->getMassactionBlock()->setFormFieldName('knowledgebase');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('knowledgebase')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('knowledgebase')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('knowledgebase/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
             'label'=> Mage::helper('knowledgebase')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'status',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('knowledgebase')->__('Status'),
                         'values' => $statuses
                     )
             )
        ));
        return $this;
    }

  public function getRowUrl($row)
  {
      return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }

}