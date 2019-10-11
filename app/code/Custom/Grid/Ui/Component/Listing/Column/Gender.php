<?php
namespace Custom\Grid\Ui\Component\Listing\Column;
use \Magento\Ui\Component\Listing\Columns\Column;

class Gender extends Column
{
      /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            
            foreach ($dataSource['data']['items'] as &$item) {
                
                if ($this->getData('gender')=='female') 
                {
                   $item[$this->setData('gender')] = 1;

                } else if ($this->getData('gender')=='male')

                {
                    $item[$this->setData('gender')] = 0;

                } else
                {
                    $item[$this->setData('gender')] = null;
                }
            }
    }
        return $dataSource;
    }
}