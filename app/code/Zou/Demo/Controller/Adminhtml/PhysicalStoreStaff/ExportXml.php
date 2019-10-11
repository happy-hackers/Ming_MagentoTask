<?php
namespace Zou\Demo\Controller\Adminhtml\PhysicalStoreStaff;

use Magento\Framework\App\Filesystem\DirectoryList;
class ExportXml extends \Zou\Demo\Controller\Adminhtml\PhysicalStoreStaff
{
    public function execute()
    {
        $fileName = 'physicalStoreStaff.xml';

        /** @var \\Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();
        $content = $resultPage->getLayout()->createBlock('Zou\Demo\Block\Adminhtml\PhysicalStoreStaff\Grid')->getXml();

        return $this->_fileFactory->create($fileName, $content, DirectoryList::VAR_DIR);
    }
}
