<?php
namespace Zou\Demo\Controller\Adminhtml\PhysicalStoreStaff;

use Magento\Framework\App\Filesystem\DirectoryList;

class ExportExcel extends \Zou\Demo\Controller\Adminhtml\PhysicalStoreStaff
{
    public function execute()
    {
        $fileName = 'physicalStoreStaff.xls';

        /** @var \\Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();
        $content = $resultPage->getLayout()->createBlock('Zou\Demo\Block\Adminhtml\PhysicalStoreStaff\Grid')->getExcel();

        return $this->_fileFactory->create($fileName, $content, DirectoryList::VAR_DIR);
    }
}
