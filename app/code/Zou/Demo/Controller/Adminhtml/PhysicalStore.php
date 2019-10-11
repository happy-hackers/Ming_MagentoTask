<?php
namespace Zou\Demo\Controller\Adminhtml;

abstract class PhysicalStore extends \Magento\Backend\App\Action
{
    const PARAM_CRUD_ID = 'id';
    protected $_physicalStoreFactory;
    protected $_physicalStoreCollectionFactory;
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Zou\Demo\Model\PhysicalStoreFactory $physicalStoreFactory,
        \Zou\Demo\Model\ResourceModel\PhysicalStore\CollectionFactory $physicalStoreCollectionFactory,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Backend\Helper\Js $jsHelper,
        \Magento\Framework\Stdlib\DateTime\DateTime $date
        ) {
            parent::__construct($context);
            $this->_coreRegistry = $coreRegistry;
            $this->_fileFactory = $fileFactory;
            $this->_storeManager = $storeManager;
            $this->_jsHelper = $jsHelper;
    
            $this->_resultPageFactory = $resultPageFactory;
            $this->_resultLayoutFactory = $resultLayoutFactory;
            $this->_resultForwardFactory = $resultForwardFactory;
    
            $this->_physicalStoreFactory = $physicalStoreFactory;
            $this->_physicalStoreCollectionFactory = $physicalStoreCollectionFactory;
            $this->_date = $date;
    }
    
    /**
     * Get back result redirect after add/edit.
     *
     * @param \Magento\Framework\Controller\Result\Redirect $resultRedirect
     * @param null                                          $paramCrudId
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    protected function _getBackResultRedirect(\Magento\Framework\Controller\Result\Redirect $resultRedirect, $paramCrudId = null)
    {
        switch ($this->getRequest()->getParam('back')) {
            case 'edit':
                $resultRedirect->setPath(
                '*/*/edit',
                [
                static::PARAM_CRUD_ID => $paramCrudId,
                '_current' => true,
                ]
                );
                break;
            case 'new':
                $resultRedirect->setPath('*/*/new', ['_current' => true]);
                break;
            default:
                $resultRedirect->setPath('*/*/');
        }
    
        return $resultRedirect;
    }
    
    /**
     * Check if admin has permissions to visit related pages.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Zou_Demo::physicalStores_physicalStores');
    }
}
