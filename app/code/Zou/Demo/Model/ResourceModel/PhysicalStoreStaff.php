<?php

/**
 * Zou
 *
 * NOTICE OF LICENSE
 *
 * DISCLAIMER
 *
 * 定义实体店员工资源模型
 *
 * @category    Zou
 * @package     Zou_Demo
 * @copyright   Copyright (c) 2018 Zou
 */

namespace Zou\Demo\Model\ResourceModel;

/**
 * PhysicalStore Resource Model
 * @category Zou
 * @package  Zou_Demo
 * @module   PhysicalStoresStaff
 * @author   Zou Developer
 */
class PhysicalStoreStaff extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * construct
     * @return void
     */
    protected function _construct()
    {
    	//传表名和主键。进行实例化
        $this->_init('physical_store_staff', 'id');
    }
}
