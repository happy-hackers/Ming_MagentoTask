<?php
namespace Custom\Email\Controller\Adminhtml\Index;

use Custom\Email\Helper\Email;

//use Magento\Customer\Controller\Adminhtml\Index;
use \Magento\Framework\App\Action\Context;
use \Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\HttpPostActionInterface as HttpPostActionInterface;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Magento\Customer\Api\CustomerMetadataInterface;



class Save extends Action implements HttpPostActionInterface
{

    /**
     * @var Email
     */
    protected $emailData;

    /**
     * @var EventManager
     */
    protected $eventManager;

    public function __construct(
        Context $context,
        Email $emailData,
        EventManager $eventManager

    )
    {
        $this->emailData = $emailData;
        $this->eventManager = $eventManager;
        return parent::__construct($context);
    }

    public function execute()
    {

        $customerId = $this->getCurrentCustomerId();

        if($customerId !== null){
            $this->eventManager->dispatch('customer_change_info', ['customer' => $customer, 'request' => $this->getRequest()]);
        }
    }
    /**
     * Retrieve current customer ID
     *
     * @return int
     */
    private function getCurrentCustomerId()
    {
        $originalRequestData = $this->getRequest()->getPostValue(CustomerMetadataInterface::ENTITY_TYPE_CUSTOMER);

        $customerId = isset($originalRequestData['entity_id'])
            ? $originalRequestData['entity_id']
            : null;

        return $customerId;
    }
}
