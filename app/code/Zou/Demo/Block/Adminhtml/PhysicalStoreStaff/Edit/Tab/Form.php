<?php
namespace Zou\Demo\Block\Adminhtml\PhysicalStoreStaff\Edit\Tab;

use Zou\Demo\Model\Status;

class Form extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    const FIELD_NAME_SUFFIX = 'physicalStoreStaff';

    /**
     * @var \Magento\Config\Model\Config\Structure\Element\Dependency\FieldFactory
     */
    protected $_fieldFactory;

    /**
     * [$_physicalStoresHelper description].
     *
     * @var \Zou\Demo\Helper\Data
     */
    protected $_physicalStoresHelper;
    protected $_physicalStoreFactory;
    /**
     * [__construct description].
     *
     * @param \Magento\Backend\Block\Template\Context                                $context            [description]
     * @param \Zou\Demo\Helper\Data                                    $physicalStoresHelper [description]
     * @param \Magento\Framework\Registry                                            $registry           [description]
     * @param \Magento\Framework\Data\FormFactory                                    $formFactory        [description]
     * @param \Magento\Store\Model\System\Store                                      $systemStore        [description]
     * @param \Magento\Config\Model\Config\Structure\Element\Dependency\FieldFactory $fieldFactory       [description]
     * @param array                                                                  $data               [description]
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Zou\Demo\Helper\Data $physicalStoresHelper,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Config\Model\Config\Structure\Element\Dependency\FieldFactory $fieldFactory,
        \Zou\Demo\Model\PhysicalStoreFactory $physicalStoreFactory,
        //\Zou\Demo\Model\PhysicalStoreStaffFactory $physicalStoreStaffFactory,
        array $data = []
    ) {
        $this->_physicalStoresHelper = $physicalStoresHelper;
        $this->_fieldFactory = $fieldFactory;
        $this->_physicalStoreFactory = $physicalStoreFactory;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    protected function _prepareLayout()
    {
        $this->getLayout()->getBlock('page.title')->setPageTitle($this->getPageTitle());
    }

    /**
     * Prepare form.
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        //$physicalStoreStaff=new \Zou\Demo\Model\PhysicalStoreStaff;
        $physicalStoreStaff = $this->getPhysicalStoreStaff();
        $isElementDisabled = true;
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        /*
         * declare dependence
         */
        // dependence block
        $dependenceBlock = $this->getLayout()->createBlock(
            'Magento\Backend\Block\Widget\Form\Element\Dependence'
        );

        // dependence field map array
        $fieldMaps = [];

        $form->setHtmlIdPrefix('page_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('PhysicalStoreStaff Information')]);
        if ($physicalStoreStaff->getId()) {
            $fieldset->addField('id', 'hidden', ['name' => 'id']);
        }

        $fieldset->addField(
            'lastname',
            'text',
            [
                'name' => 'lastname',
                'label' => __('LastName'),
                'title' => __('LastName'),
                'required' => true,
                'class' => 'required-entry',
            ]
        );
        $fieldset->addField(
            'firstname',
            'text',
            [
                'name' => 'firstname',
                'label' => __('FirstName'),
                'title' => __('FirstName'),
                'required' => true,
                'class' => 'required-entry',
            ]
            );
        
        $fieldset->addField(
            'title',
            'text',
            [
                'name' => 'title',
                'label' => __('Title'),
                'title' => __('Title'),
                'required' => true,
                'class' => 'required-entry',
            ]
            );
        
        $fieldset->addField(
            'image_holder',
            'select',
            [
                'name' => 'image_holder',
                'label' => __('Image Holder'),
                'title' => __('Image Holder'),
                'values' => $this->getImageHolder(),
                'class' => 'required-entry'
            ]
            ); 
        
        $fieldset->addField(
            'image',
            'image',
            [
                'name' => 'image',
                'label' => __('Image'),
                'title' => __('Image'),
                'required' => false,
                'class' => 'required-entry',
                'note' => 'Allow image type: jpg, jpeg, gif, png',
            ]
            );
       
        
        $fieldset->addField(
            'email',
            'text',
            [
                'name' => 'email',
                'label' => __('Email'),
                'title' => __('Email'),
                /* 'required' => true, 
                'class' => 'required-entry',*/
            ]
            );
        $fieldset->addField(
            'phone',
            'text',
            [
                'name' => 'phone',
                'label' => __('Phone'),
                'title' => __('Phone'),
                'required' => true,
                'class' => 'required-entry',
            ]
            );
        
         $physicalStore = $physicalStoreStaff->getAvailablePhysicalStore();
         $fieldset->addField(
            'store_id',
            'select',
            [
                'name' => 'store_id',
                'label' => __('Physical_Store'),
                'title' => __('Physical_Store'),
                'required' => true,
                'values' =>$physicalStore,
                'class' => 'required-entry'
            ]
            ); 
        
        $fieldset->addField(
             'sort_order',
             'text',
             [
                 'name' => 'sort_order',
                 'label' => __('Sort Order'),
                 'title' => __('Sort Order'),
                 'required' => false,
                 //'class' => 'required-entry'
             ]
             );
        $fieldset->addField(
            'status',
            'select',
            [
                'name' => 'status',
                'label' => __('Status'),
                'title' => __('Status'),
                'required' => true,
                'values' => Status::getAvailableStatuses(),
                'class' => 'required-entry'
            ]
        );
        
        $form->setValues($physicalStoreStaff->getData());
        $form->addFieldNameSuffix(self::FIELD_NAME_SUFFIX);
        $this->setForm($form);

        return parent::_prepareForm();
    }
    

    public function getPhysicalStoreStaff()
    {
        return $this->_coreRegistry->registry('physicalStoreStaff');
    }
    public function getPageTitle()
    {
        return $this->getPhysicalStoreStaff()->getId() ? __("Edit PhysicalStoreStaff '%1'", $this->escapeHtml($this->getPhysicalStoreStaff()->getFirstname())) : __('New PhysicalStoreStaff');
    }

    /**
     * Prepare label for tab.
     *
     * @return string
     */
    public function getTabLabel()
    {
        return __('PhysicalStoreStaff Information');
    }

    /**
     * Prepare title for tab.
     *
     * @return string
     */
    public function getTabTitle()
    {
        return __('PhysicalStoreStaff Information');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }
    
    protected  function getImageHolder()
    {
        return array(
            array(
                'value' => 0,
                'label' => 'Female'
            ),
            array(
                'value' => 1,
                'label' => 'Male'
            )
        );
    }
}
