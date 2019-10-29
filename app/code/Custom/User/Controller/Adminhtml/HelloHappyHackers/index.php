<?php
namespace Custom\User\Controller\Adminhtml\HelloHappyHackers;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface as HttpGetActionInterface;
use Magento\Framework\View\Result\PageFactory;
use \Magento\Backend\App\Action;

class Index extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Custom_User::happyhacker';

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * Constructor
     *
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Load the page defined in view/adminhtml/layout/happyadminpage_hellohappyhackers_index.xml
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Custom_User::customer_info');
        $resultPage->getConfig()->getTitle()->prepend(__('HH Customer Information'));
        $resultPage->addBreadcrumb(__('Customers'), __('Customers'));
        $resultPage->addBreadcrumb(__('HH Customer Information'), __('HH Customer Information'));
        return $resultPage;
    }
}
