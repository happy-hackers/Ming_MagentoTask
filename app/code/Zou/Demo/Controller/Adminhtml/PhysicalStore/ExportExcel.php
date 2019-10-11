<?php

namespace Zou\Demo\Controller\Adminhtml\PhysicalStore;

use Magento\Framework\App\Filesystem\DirectoryList;

class ExportExcel extends \Zou\Demo\Controller\Adminhtml\PhysicalStore
{
    public function execute()
    {
        $fileName = 'physicalStore.xls';

        /** @var \\Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();
        $content = $resultPage->getLayout()->createBlock('Zou\Demo\Block\Adminhtml\PhysicalStore\Grid')->getExcel();

        return $this->_fileFactory->create($fileName, $content, DirectoryList::VAR_DIR);
    }
}
