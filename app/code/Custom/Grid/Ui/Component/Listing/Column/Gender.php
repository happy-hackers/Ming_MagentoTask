<?php
namespace Custom\Grid\Ui\Component\Listing\Column;

use \Magento\Framework\View\Element\UiComponent\ContextInterface;
use \Magento\Framework\View\Element\UiComponentFactory;
use \Magento\Ui\Component\Listing\Columns\Column;
class Gender extends Column
{
    
    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param array $components
     * @param array $data
     */
    
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = []
    ) {

        parent::__construct($context, $uiComponentFactory, $components, $data);
    }
    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {

            foreach ($dataSource['data']['items'] as & $item) {
               if (isset($item[$this->getData('name')]))
               {
                if ('1' == $item[$this->getData('name')]){
                    $item['gender'] = 'Male';
                } elseif ('2' == $item[$this->getData('name')]){
                    $item['gender'] = 'Female';
                } elseif ('3' == $item[$this->getData('name')]){
                    $item['gender'] = 'No Specified';
                }
               }
            }
    }
        return $dataSource;
    }
}