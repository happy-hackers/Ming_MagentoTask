<?php

namespace Zou\Demo\Controller\Adminhtml\PhysicalStore;

use Magento\Framework\App\Filesystem\DirectoryList;

class ExportXml extends \Zou\Demo\Controller\Adminhtml\PhysicalStore
{
    public function execute()
    {
        $fileName = 'physicalStore.xml';

        /** @var \\Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();
        $content = $resultPage->getLayout()->createBlock('Zou\Demo\Block\Adminhtml\PhysicalStore\Grid')->getXml();

        return $this->_fileFactory->create($fileName, $content, DirectoryList::VAR_DIR);
    }
}
