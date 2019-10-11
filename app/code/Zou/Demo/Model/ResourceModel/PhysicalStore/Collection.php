<?php

/**
 * Zou
 *
 * NOTICE OF LICENSE
 *
 * DISCLAIMER
 *
 * 定义实体店数据集模型
 *
 * @category    Zou
 * @package     Zou_Demo
 * @copyright   Copyright (c) 2018 Zou
 */

namespace Zou\Demo\Model\ResourceModel\PhysicalStore;

/**
 * PhysicalStore Collection
 * @category Zou
 * @package  Zou_Demo
 * @module   PhysicalStores
 * @author   Zou Developer
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * _construct
     * @return void
     */
    protected function _construct()
    {
    	//传递实体店模型类和资源类 进行实例化
        $this->_init('Zou\Demo\Model\PhysicalStore', 'Zou\Demo\Model\ResourceModel\PhysicalStore');
    }

}
