<?php
namespace Webkul\LayeredNavigation\Model\Layer;

class Resolver extends \Magento\Catalog\Model\Layer\Resolver
{
	public function __construct(
		\Magento\Framework\ObjectManagerInterface $objectManager,
		\Webkul\LayeredNavigation\Model\Layer $layer,
		array $layersPool
	) {
		$this->layer = $layer;
		parent::__construct($objectManager, $layersPool);
	}

	public function create($layerType)
	{
		
	}
}