<?php
namespace Zou\Demo\Controller\Adminhtml\PhysicalStore;

use Magento\Framework\App\Filesystem\DirectoryList;

/**
 * Export Csv action
 * @category Iggo
 * @package  Zou_Demo
 * @module   PhysicalStores
 * @author   Iggo Developer
 */
class ExportCsv extends \Zou\Demo\Controller\Adminhtml\PhysicalStore
{
    public function execute()
    {
        $fileName = 'physicalStore.csv';

        /** @var \\Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();
        $content = $resultPage->getLayout()->createBlock('Zou\Demo\Block\Adminhtml\PhysicalStore\Grid')->getCsv();

        return $this->_fileFactory->create($fileName, $content, DirectoryList::VAR_DIR);
    }
}
