<?php
namespace Magestore\HelloMagento\Model;
use Magestore\HelloMagento\Api\TestApi;
use Magento\Catalog\Model\ProductFactory;

class TestApiData implements TestApi
{
    protected $productFactory;
    public function __construct(
        ProductFactory $productFactory
    ) {
        $this->productFactory = $productFactory;
    }

    public function getApiData($sku)
    {
        $productid = $this->resolveProductId($sku);
        if(is_numeric($productid)) {
            $mainarry = [];
            $ary_response = [];
            $valid = [
                "code" => "200",
                "sku" => $sku,
                "product_id" => $productid
            ];
            $ary_response[] = $valid;
        } else {
            $ary_response[] = $productid;
        }
        return $ary_response;
    }
     /**
     * @param string $productSku
     * @return mixed
     */
    protected function resolveProductId($productSku)
    {
        /** @var \Magento\Catalog\Model\Product $product */
        $product = $this->productFactory->create();
        $productId = $product->getIDBySku($productSku);
        if (!$productId) {
            $invalid = ["code" => '301' , "message" => 'SKU' . $productSku . "Not Found On Magento!"];
            return $invalid;
        }
        return $productId;
    }
}
