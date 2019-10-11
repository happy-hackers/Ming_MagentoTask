<?php

/**
 * Zou
 *
 * NOTICE OF LICENSE
 *
 * DISCLAIMER
 *
 * 定义实体店模型
 *
 * @category    Zou
 * @package     Zou_Demo
 * @copyright   Copyright (c) 2018 Zou
 */

namespace Zou\Demo\Model;

/**
 * PhysicalStore Model
 * @category Zou
 * @package  Zou_Demo
 * @module   PhysicalStores
 * @author   Zou Developer
 */
class PhysicalStore extends \Magento\Framework\Model\AbstractModel
{
    //定义实体店图片存放路径，放在pub/media/zou/physical_stores下面
    const BASE_MEDIA_PATH = 'zou/physical_stores';
    protected $_countryCollection;
    protected $_countryOptions;

     /**
     * constructor.
     *
     * @param \Magento\Framework\Model\Context                                $context
     * @param \Magento\Framework\Registry                                     $registry
     * @param \Zou\Demo\Model\ResourceModel\Banner\CollectionFactory $bannerCollectionFactory
     * @param \Zou\Demo\Model\ResourceModel\PhysicalStore                   $resource
     * @param \Zou\Demo\Model\ResourceModel\PhysicalStore\Collection        $resourceCollection
     */
    //构造函数，传递PhysicalStore和PhysicalStore\Collection 两个参数，实例化
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Zou\Demo\Model\ResourceModel\PhysicalStore $resource,
        \Zou\Demo\Model\ResourceModel\PhysicalStore\Collection $resourceCollection,
        \Magento\Directory\Model\ResourceModel\Country\Collection $countryCollection
    ) {
        parent::__construct(
            $context,
            $registry,
            $resource,
            $resourceCollection
        );
        $this->_countryCollection = $countryCollection;
    }

    

    //自定义函数 获取国家列表
    public function getAvailableCountry($isMultiselect = false, $foregroundCountries ='')
    {
        $this->_countryOptions = $this->_countryCollection->loadData()->setForegroundCountries(
                $foregroundCountries
                )->toOptionArray(
                    false
                    );
        $options = $this->_countryOptions;
        if (!$isMultiselect) {
            array_unshift($options, ['value' => '', 'label' => __('--Please Select--')]);
        }
        
        return $options;
    }
    /* public function getWebsites($isMultiselect = false){
            //$helper = $this->helper('Iggo\Theme2016\Helper\Data');
            $stores =$this->_helper->getStores();
            foreach ($stores as $k=>$store){
                $newStores[$k]['value']= $store->getWebsiteId();
                $newStores[$k]['label']= $store->getName();
            }
            if (!$isMultiselect) {
                array_unshift($newStores, ['value' => '', 'label' => __('--Please Select--')]);
            }
            return $newStores;
    } */
}
