<?php
namespace Zou\Demo\Block\Adminhtml\PhysicalStore\Edit\Tab;

use Zou\Demo\Model\Status;

class Form extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface {

    const FIELD_NAME_SUFFIX = 'physicalStore';

    protected $_fieldFactory;
    protected $_physicalStoresHelper;
    protected $_systemStore;
    protected $_scopeConfig;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context, 
        \Zou\Demo\Helper\Data $physicalStoresHelper, 
        \Magento\Framework\Registry $registry, 
        \Magento\Framework\Data\FormFactory $formFactory, 
        \Magento\Config\Model\Config\Structure\Element\Dependency\FieldFactory $fieldFactory, 
        \Magento\Store\Model\System\Store $systemStore, 
        array $data = []
    ) {
        $this->_physicalStoresHelper = $physicalStoresHelper;
        $this->_systemStore = $systemStore;
        $this->_fieldFactory = $fieldFactory;
        $this->_scopeConfig = $context->getScopeConfig();
        parent::__construct($context, $registry, $formFactory, $data);
    }

    protected function _prepareLayout() {
        $this->getLayout()->getBlock('page.title')->setPageTitle($this->getPageTitle());
    }
    
    protected function _prepareForm() {
        $physicalStore = $this->getPhysicalStore();
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

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('PhysicalStore Information')]);
        if ($physicalStore->getId()) {
            $fieldset->addField('id', 'hidden', ['name' => 'id']);
        }

        $fieldset->addField(
            'name', 'text', [
            'name' => 'name',
            'label' => __('Name'),
            'title' => __('Name'),
            'required' => true,
            'class' => 'required-entry',
            ]
        );
        $fieldset->addField(
            'url_key', 'text', [
            'name' => 'url_key',
            'label' => __('URL'),
            'title' => __('URL'),
            'required' => true,
            'class' => 'required-entry',
            ]
        );

        $fieldset->addField(
            'street', 'text', [
            'name' => 'street',
            'label' => __('Street'),
            'title' => __('Street')
            ]
        );

        $fieldset->addField(
            'description', 'Zou\Demo\Block\Adminhtml\Form\Element\Editor', [
            'name' => 'description',
            'label' => __('Description'),
            'title' => __('Description'),
            'class' => 'required-entry',
            'wysiwyg' => true,
            'required' => false,
            ]
        );
        /* $fieldset->addField(
          'image',
          'image',
          [
          'name' => 'image',
          'label' => __('Image'),
          'title' => __('Image'),
          'required' => true,
          'class' => 'required-entry',
          'note' => 'Allow image type: jpg, jpeg, gif, png',
          ]
          ); */
        $openHoursField = $fieldset->addField(
            'opening_hours', 'text', [
            'name' => 'opening_hours',
            'label' => __('Opening Hours'),
            'title' => __('Opening Hours')
            ]
        );
        $renderer = $this->getLayout()->createBlock('Zou\Demo\Block\Adminhtml\Form\Renderer\OpeningHours');

        $openHoursField->setRenderer($renderer);

        $fieldset->addField(
            'email', 'text', [
            'name' => 'email',
            'label' => __('Email'),
            'title' => __('Email'),
            ]
        );
        $fieldset->addField(
            'phone', 'text', [
            'name' => 'phone',
            'label' => __('Phone'),
            'title' => __('Phone')
            ]
        );
        $fieldset->addField(
            'city', 'text', [
            'name' => 'city',
            'label' => __('City'),
            'title' => __('City'),
            'required' => true,
            'class' => 'required-entry',
            ]
        );
        $fieldset->addField(
            'postcode', 'text', [
            'name' => 'postcode',
            'label' => __('Postcode'),
            'title' => __('Postcode')
            ]
        );

        $country = $physicalStore->getAvailableCountry();
        $fieldset->addField(
            'country_code', 'select', [
            'name' => 'country_code',
            'label' => __('Country'),
            'title' => __('Country'),
            'required' => true,
            'values' => $country,
            'class' => 'required-entry',
            ]
        );


        $fieldset->addField(
            'latitude', 'text', [
            'name' => 'latitude',
            'label' => __('Latitude'),
            'title' => __('Latitude'),
            ]
        );
        $fieldset->addField(
            'longitude', 'text', [
            'name' => 'longitude',
            'label' => __('Longitude'),
            'title' => __('Longitude'),
            ]
        );

        $fieldset->addField(
            'website', 'text', [
            'name' => 'website',
            'label' => __('Website URL'),
            'title' => __('Website URL')
            ]
        );

        $fieldset->addField(
            'custom_field1', 'text', [
            'name' => 'custom_field1',
            'label' => __('Custom Field #1'),
            'title' => __('Custom Field #1'),
            'note' => $this->_scopeConfig->getValue('demo/physicalstores/custom_field1_desc')
            ]
        );

        $fieldset->addField(
            'custom_field2', 'text', [
            'name' => 'custom_field2',
            'label' => __('Custom Field #2'),
            'title' => __('Custom Field #2'),
            'note' => $this->_scopeConfig->getValue('demo/physicalstores/custom_field2_desc')
            ]
        );

        $fieldset->addField(
            'mage_store_ids', 'multiselect', [
            'name' => 'mage_store_ids',
            'label' => __('Store View'),
            'title' => __('Store View'),
            'required' => true,
            'values' => $this->_systemStore->getStoreValuesForForm(false, true),
            'class' => 'required-entry',
            ]
        );
        $fieldset->addField(
            'status', 'select', [
            'name' => 'status',
            'label' => __('Status'),
            'title' => __('Status'),
            'required' => true,
            'values' => Status::getAvailableStatuses(),
            'class' => 'required-entry'
            ]
        );
        $form->setValues($physicalStore->getData());
        // $form->addFieldNameSuffix(self::FIELD_NAME_SUFFIX);
        $this->setForm($form);

        return parent::_prepareForm();
    }

    public function getWebsites() {
        $stores = array();
        $store = $this->_physicalStoresHelper->getAllStores();
        foreach ($store as $k => $v) {
            $stores[$k]['value'] = $v->getWebsiteId();
            $stores[$k]['label'] = $v->getName();
        }
        array_unshift($stores, ['value' => '', 'label' => __('All Store Views')]);
        return $stores;
    }

    public function getPhysicalStore() {
        return $this->_coreRegistry->registry('physicalStore');
    }

    public function getPageTitle() {
        return $this->getPhysicalStore()->getId() ? __("Edit PhysicalStore '%1'", $this->escapeHtml($this->getPhysicalStore()->getName())) : __('New PhysicalStore');
    }

    /**
     * Prepare label for tab.
     *
     * @return string
     */
    public function getTabLabel() {
        return __('PhysicalStore Information');
    }

    /**
     * Prepare title for tab.
     *
     * @return string
     */
    public function getTabTitle() {
        return __('PhysicalStore Information');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab() {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden() {
        return false;
    }

}
