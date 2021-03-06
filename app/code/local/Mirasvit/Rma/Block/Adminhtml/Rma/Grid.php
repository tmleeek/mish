<?php
class Mirasvit_Rma_Block_Adminhtml_Rma_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    protected $_customFilters = array();
    public function __construct()
    {
        parent::__construct();
        $this->setId('grid');
        $this->setDefaultSort('updated_at');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    public function addCustomFilter($field, $filter) {
        $this->_customFilters[$field] = $filter;
        return $this;
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('rma/rma')
            ->getCollection();
        foreach ($this->_customFilters as $key => $value) {
            $collection->addFieldToFilter($key, $value);
        }
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {

        $this->addColumn('increment_id', array(
            'header'    => Mage::helper('rma')->__('RMA #'),
//          'align'     => 'right',
//          'width'     => '50px',
            'index'     => 'increment_id',
            'filter_index'     => 'main_table.increment_id',
            )
        );
        $this->addColumn('order_increment_id', array(
            'header'    => Mage::helper('rma')->__('Order #'),
//          'align'     => 'right',
//          'width'     => '50px',
            'index'     => 'order_increment_id',
            'filter_index'     => 'order.increment_id',
            )
        );
        $this->addColumn('name', array(
            'header'    => Mage::helper('rma')->__('Customer Name'),
//          'align'     => 'right',
//          'width'     => '50px',
            'index'     => 'name',
            )
        );
        $this->addColumn('user_id', array(
            'header'    => Mage::helper('rma')->__('Owner'),
//          'align'     => 'right',
//          'width'     => '50px',
            'index'     => 'user_id',
            'type'      => 'options',
            'options'   => Mage::helper('rma')->getAdminUserOptionArray(),
            )
        );
        $this->addColumn('last_reply_name', array(
            'header'    => Mage::helper('rma')->__('Last Replier'),
//          'align'     => 'right',
//          'width'     => '50px',
            'index'     => 'last_reply_name',
            )
        );
        $this->addColumn('status_id', array(
            'header'    => Mage::helper('rma')->__('Status'),
//          'align'     => 'right',
//          'width'     => '50px',
            'index'     => 'status_id',
            'type'      => 'options',
            'options'   => Mage::getModel('rma/status')->getCollection()->getOptionArray(),
            'filter_index'     => 'main_table.status_id',
            )
        );
        $this->addColumn('created_at', array(
            'header'    => Mage::helper('rma')->__('Created Date'),
//          'align'     => 'right',
//          'width'     => '50px',
            'index'     => 'created_at',
            'type'      => 'datetime',
            'filter_index'     => 'main_table.created_at',
            )
        );
        $this->addColumn('updated_at', array(
            'header'    => Mage::helper('rma')->__('Last Activity'),
//          'align'     => 'right',
//          'width'     => '50px',
            'index'     => 'updated_at',
            'type'      => 'datetime',
            'frame_callback'   => array($this, '_lastActivityFormat'),
            'filter_index'     => 'main_table.updated_at',
            )
        );
        if ($this->getExportVisibility() !== false) {
            $this->addExportType('*/*/exportCsv', Mage::helper('rma')->__('CSV'));
            $this->addExportType('*/*/exportXml', Mage::helper('rma')->__('XML'));
        }
        return parent::_prepareColumns();
    }

    public function _lastActivityFormat($renderedValue, $row, $column, $isExport)
    {
        return Mage::helper('rma/string')->nicetime(strtotime($row['updated_at']));
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('rma_id');
        $this->getMassactionBlock()->setFormFieldName('rma_id');
        $statuses = array(
                array('label'=>'', 'value'=>''),
                array('label'=>$this->__('Disabled'), 'value'=> 0),
                array('label'=>$this->__('Enabled'), 'value'=> 1),
        );
        $this->getMassactionBlock()->addItem('delete', array(
            'label'    => Mage::helper('rma')->__('Delete'),
            'url'      => $this->getUrl('*/*/massDelete'),
            'confirm'  => Mage::helper('rma')->__('Are you sure?')
        ));
        return $this;
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('rmaadmin/adminhtml_rma/edit', array('id' => $row->getId()));
    }

    /************************/

}