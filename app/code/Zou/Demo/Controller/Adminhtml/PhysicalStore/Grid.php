<?php
namespace Zou\Demo\Controller\Adminhtml\PhysicalStore;

class Grid extends \Zou\Demo\Controller\Adminhtml\PhysicalStore
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        $resultLayout = $this->_resultLayoutFactory->create();
        
        return $resultLayout;
    }
}
