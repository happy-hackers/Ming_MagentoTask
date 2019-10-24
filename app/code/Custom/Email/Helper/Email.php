<?php

namespace Custom\Email\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Framework\Escaper;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\MailException;
use Magento\Store\Api\Data\StoreInterface;
use \Magento\Framework\App\Helper\Context;

/**
 * Custom Module Email helper
 */
class Email extends AbstractHelper
{
    const XML_PATH_PATH_EMAIL_TEMPLATE_FIELD= 'happyhackers/customEmail/';

    /**
     * @var StateInterface
     */
    protected $inlineTranslation;

    /**
     * @var Escaper
     */
    protected $escaper;

    /**
     * @var TransportBuilder
     */
    protected $transportBuilder;

    /**
     * Store manager
     *
     * @var StoreManagerInterface
     */
    protected $storeManager;

    public function __construct(
        Context $context,
        StateInterface $inlineTranslation,
        Escaper $escaper,
        TransportBuilder $transportBuilder
    ) {
        parent::__construct($context);
        $this->inlineTranslation = $inlineTranslation;
        $this->escaper = $escaper;
        $this->transportBuilder = $transportBuilder;
    }

    /**
     * Return store
     * @return StoreInterface
     * @throws NoSuchEntityException
     */
    public function getStore()
    {
        return $this->storeManager->getStore();
    }

    /**
     * Return config value
     * @param $field
     * @param null $storeId
     * @return mixed
     */
    public function getConfigValue($field, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            $field, ScopeInterface::SCOPE_STORE, $storeId
        );
    }

    /**
     * Generate config value by code
     * @param $code
     * @param null $storeId
     * @return mixed
     */
    public function getGeneralConfig($code, $storeId = null)
    {
        // return path with different fiedls '{section}/{group}/{field}'
        return $this->getConfigValue(self::XML_PATH_PATH_EMAIL_TEMPLATE_FIELD.$code, $storeId);
    }

    /**
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function emailSender(){
        return $this->getGeneralConfig('email', $this->getStore()->getId());
    }

    /**
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function emailSenderName(){
        return $this->getGeneralConfig('name',$this->getStore()->getId());
    }

    /**
     * [generateTemplate description]
     * @param $emailTemplateVariables
     * @param $senderInfo
     * @param $receiverInfo
     * @param $templateId
     * @throws NoSuchEntityException
     */
    public function generateTemplate($emailTemplateVariables,$senderInfo,$receiverInfo,$templateId)
    {
        $template =$this->transportBuilder->setTemplateIdentifier($templateId);
        $template->setFrom($senderInfo);
        $template->setTemplateVars($emailTemplateVariables);
        $template->setTemplateOptions(
        [
            'area' => \Magento\Framework\App\Area::AREA_FRONTEND, /* here you can defile area and store of template for which you prepare it */
            'store' => $this->getStore()->getId(),
        ]);
        $template->addTo($receiverInfo['email'],$receiverInfo['name']);
    }

    /**
     * [sendEmail description]
     * @param $emailTemplateVariables
     * @param $senderInfo
     * @param $receiverInfo
     * @return $this
     * @throws MailException
     * @throws NoSuchEntityException
     */
    public function sendEmail($emailTemplateVariables,$senderInfo,$receiverInfo)
    {
        $templateId = $this->getGeneralConfig('template',$this->getStore()->getId());
        $this->inlineTranslation->suspend();
        $this->generateTemplate($emailTemplateVariables,$senderInfo,$receiverInfo,$templateId);
        $transport = $this->transportBuilder->getTransport();
        $transport->sendMessage();
        $this->inlineTranslation->resume();
        return $this;
    }


}