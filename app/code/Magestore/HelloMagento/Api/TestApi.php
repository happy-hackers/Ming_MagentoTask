<?php
namespace Magestore\HelloMagento\Api;
interface TestApi
{
    /**
     * @api
     * @param string $sku
     * @return array
     */
    public function getApiData($sku);
}