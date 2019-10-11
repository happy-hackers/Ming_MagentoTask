<?php
namespace Zou\Demo\Model;

class Status
{
    const STATUS_DISABLED = 0;
    const STATUS_ENABLED = 1;

    /**
     * get available statuses.
     *
     * @return []
     */
    public static function getAvailableStatuses()
    {
        return [
            self::STATUS_DISABLED => __('Inactive'),
            self::STATUS_ENABLED => __('Active')
        ];
    }
    public static function getAvailableStatusesValues()
    {
        return [
            ['value'=>self::STATUS_DISABLED,'label'=>__('Inactive')],
            ['value'=>self::STATUS_ENABLED,'label'=>__('Active')]
        ];
    }
   
}
