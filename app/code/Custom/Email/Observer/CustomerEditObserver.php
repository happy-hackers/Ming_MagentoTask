<?php

namespace Custom\Email\Observer;


use Magento\Framework\Event\ObserverInterface;
use Custom\Email\Helper\Email;
use Magento\Framework\Exception\MailException;
use  Magento\Framework\Exception\NoSuchEntityException;



class CustomerEditObserver implements ObserverInterface
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

        $customer = $observer->getCustomer();
        $email = $customer->getEmail();
        $name = $customer->getFirstname(). ' ' . $customer->getLastname();
        /* Here we prepare data for our email  */
        /* Receiver Detail  */

        $receiverInfo = [
            'name' => $name,
            'email' => $email,
        ];

        /* Sender Detail  */
        $senderInfo = [
            'name' => $this->helperEmail->emailSenderName(),
            'email' => $this->helperEmail->emailSender(),
        ];

        /* Assign values for your template variables  */
        $emailTemplateVariables = array();
        $emailTemplateVariables['customer_name'] = $name;
        $emailTemplateVariables['customer_email'] = $email;
        $emailTemplateVariables['sender_email'] = $this->helperEmail->emailSender();
        return $this->helperEmail->sendEmail($emailTemplateVariables,$senderInfo,$receiverInfo);
    }



}