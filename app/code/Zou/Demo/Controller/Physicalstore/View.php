<?php
namespace Zou\Demo\Controller\Physicalstore;

use Magento\Customer\Controller\AccountInterface;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class View extends \Magento\Framework\App\Action\Action
{
	/**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $_request;

    /**
     * @var \Magento\Framework\App\ResponseInterface
     */
    protected $_response;

    /**
     * @var \Magento\Framework\Controller\Result\RedirectFactory
     */
    protected $resultRedirectFactory;

    /**
     * @var \Magento\Framework\Controller\ResultFactory
     */
    protected $resultFactory;

    /**
     * @var \Ves\Blog\Helper\Data
     */
    protected $_physicalStoresHelper;
    protected $_physicalStore;
    /**
     * @var \Magento\Framework\Controller\Result\ForwardFactory
     */
    protected $resultForwardFactory;

    /**
     * @param Context
     * @param \Magento\Store\Model\StoreManager
     * @param \Magento\Framework\View\Result\PageFactory
     * @param \Ves\Blog\Helper\Data
     * @param \Magento\Framework\Controller\Result\ForwardFactory
     * @param \Magento\Framework\Registry
     */
    public function __construct(
        Context $context,
        \Magento\Store\Model\StoreManager $storeManager,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Zou\Demo\Helper\Data $physicalStoresHelper,
        \Zou\Demo\Model\PhysicalStore $physicalStore,
        \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory,
        \Magento\Framework\Registry $registry
        ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->_physicalStoresHelper = $physicalStoresHelper;
        $this->resultForwardFactory = $resultForwardFactory;
        $this->_coreRegistry = $registry;
        $this->_physicalStore = $physicalStore;
        parent::__construct($context);
    }

    /**
     * Default customer account page
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $page = $this->resultPageFactory->create();
        if($urlKey = $this->getRequest()->getParam('url_key')){
            $physicalstore = $this->_physicalStore->getCollection()->addFieldToFilter("url_key", $urlKey)
                ->getFirstItem();
            $this->_coreRegistry->register("current_physicalstore", $physicalstore);
        }
        $pyStore = $this->_coreRegistry->registry('current_physicalstore');
        //$physicalStoresHelper = $this->_physicalStoresHelper;
        //if(!$pyStore->getStatus()){
        if(!$pyStore || !$pyStore->getId()){
            return $this->resultForwardFactory->create()->forward('noroute');
        }
        $page_layout = '1column';
        $page->getConfig()->setPageLayout($page_layout);
        return $page;
    }
}