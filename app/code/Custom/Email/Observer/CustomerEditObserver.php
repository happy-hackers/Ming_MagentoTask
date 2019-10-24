<?php

namespace Custom\Email\Observer;


use Magento\Framework\Event\ObserverInterface;
use Custom\Email\Helper\Email;
use Magento\Framework\Exception\MailException;
use  \Magento\Framework\Exception\NoSuchEntityException;


class CustomerRegisterObserver implements ObserverInterface
{
    private $helperEmail;

    public function __construct(
        Email $helperEmail
    ) {
        $this->helperEmail = $helperEmail;
    }

    /**
     * @param  $observer
     * @return Email|void
     * @throws MailException
     * @throws NoSuchEntityException
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $customerData = $observer->getData('customer');
        /* Here we prepare data for our email  */
        /* Receiver Detail  */
        $customer_name = $customerData['firstname'] .' ' . $customerData['lastname'];
        $receiverInfo = [
            'name' => $customer_name,
            'email' => $customerData['email'],
        ];

        /* Sender Detail  */
        $senderInfo = [
            'name' => $this->helperEmail->emailSenderName(),
            'email' => $this->helperEmail->emailSender(),
        ];

        /* Assign values for your template variables  */
        $emailTemplateVariables = array();
        $emailTemplateVariables['customer_name'] = $customer_name;
        $emailTemplateVariables['customer_email'] = $customerData['email'];
        $emailTemplateVariables['sender_email'] = $this->helperEmail->emailSender();
        return $this->helperEmail->sendEmail($emailTemplateVariables,$senderInfo,$receiverInfo);
    }



}