<?php
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Custom\Grid\Controller\Adminhtml\Info;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface as HttpGetActionInterface;
use Magento\Framework\View\Result\PageFactory;


class Index extends \Magento\Backend\App\Action implements HttpGetActionInterface
{   
    /**
    * Authorization level of a basic admin session
    *
    * @see _isAllowed()
    */
   const ADMIN_RESOURCE = 'Custom_Grid::info';

   /**
    * @var PageFactory
    */
   protected $resultPageFactory;

   /**
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
     * HH Customer Information list.
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Custom_Grid::customer_info');
        $resultPage->getConfig()->getTitle()->prepend(__('HH Customer Information'));
        $resultPage->addBreadcrumb(__('Customers'), __('Customers'));
        $resultPage->addBreadcrumb(__('HH Customer Information'), __('HH Customer Information'));
        return $resultPage;

    }
}
