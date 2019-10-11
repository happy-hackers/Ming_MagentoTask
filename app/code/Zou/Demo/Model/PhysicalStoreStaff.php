<?php

/**
 * Zou
 *
 * NOTICE OF LICENSE
 *
 * DISCLAIMER
 *
 * 定义实体店员工表模型
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
 * @module   PhysicalStoresStaff
 * @author   Zou Developer
 */
class PhysicalStoreStaff extends \Magento\Framework\Model\AbstractModel
{
    //定义实体店员工图片存放路径，放在pub/media/zou/physical_stores下面
    const BASE_MEDIA_PATH = 'zou/physical_stores';
     /**
     * constructor.
     *
     * @param \Magento\Framework\Model\Context                                $context
     * @param \Magento\Framework\Registry                                     $registry
     * @param \Zou\Demo\Model\ResourceModel\Banner\CollectionFactory $bannerCollectionFactory
     * @param \Zou\Demo\Model\ResourceModel\PhysicalStoreStaff                   $resource
     * @param \Zou\Demo\Model\ResourceModel\PhysicalStoreStaff\Collection        $resourceCollection
     */
    //构造函数，传递PhysicalStoreStaff和PhysicalStoreStaff\Collection 两个参数，实例化
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Zou\Demo\Model\ResourceModel\PhysicalStoreStaff $resource,
        \Zou\Demo\Model\ResourceModel\PhysicalStoreStaff\Collection $resourceCollection,
        \Zou\Demo\Model\ResourceModel\PhysicalStore\CollectionFactory $physicalStoreCollectionFactory
    ) {
        parent::__construct(
            $context,
            $registry,
            $resource,
            $resourceCollection
        );
        $this->_physicalStoreCollectionFactory = $physicalStoreCollectionFactory;
    }

    //自定义函数，获取可用的实体店
    public function getAvailablePhysicalStore()
    {
        $option[] = [
            'value' => '',
            'label' => __('-------- Please select a store --------'),
        ];
        $physicalStoreCollection = $this->_physicalStoreCollectionFactory->create();
        foreach ($physicalStoreCollection as $physicalStore) {
            $option[] = [
                'value' => $physicalStore->getId(),
                'label' => $physicalStore->getName(),
            ];
        }
        //usort($option, array($this, '_sortByName'));
        return $option;
    }
   
}
