<?php

declare(strict_types=1);

/**
* Digit Software Solutions.
*
* NOTICE OF LICENSE
*
* This source file is subject to the EULA
* that is bundled with this package in the file LICENSE.txt.
*
* @category  Dss
* @package   Dss_RefundRequest
* @author    Extension Team
* @copyright Copyright (c) 2024 Digit Software Solutions. ( https://digitsoftsol.com )
*/
namespace Dss\RefundRequest\Helper;

use Exception;
use Magento\Framework\Escaper;
use Magento\Store\Model\Store;
use Magento\Framework\App\Area;
use Dss\RefundRequest\Helper\Data;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;

class Email extends AbstractHelper
{
    /**
     * Email constructor.
     *
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Dss\RefundRequest\Helper\Data $helper
     * @param \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation
     * @param \Magento\Framework\Escaper $escaper
     * @param \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     */
    public function __construct(
        protected Context $context,
        protected Data $helper,
        protected StateInterface $inlineTranslation,
        protected Escaper $escaper,
        protected TransportBuilder $transportBuilder,
        protected ManagerInterface $messageManager
    ) {
        parent::__construct($context);
        $this->scopeConfig = $context->getScopeConfig();
    }

    /**
     * Send Email
     *
     * @param mixed $receivers
     * @param mixed $emailTemplate
     * @param mixed $templateVar
     * @return void
     */
    public function sendEmail($receivers, $emailTemplate, $templateVar): void
    {
        try {
            $email = $this->helper->configSenderEmail();
            $emailValue = 'trans_email/ident_' . $email . '/email';
            $emailNameValue = 'trans_email/ident_' . $email . '/name';
            $emailNameSender = $this->scopeConfig->getValue($emailNameValue, ScopeInterface::SCOPE_STORE);
            $emailSender = $this->scopeConfig->getValue($emailValue, ScopeInterface::SCOPE_STORE);
            $this->inlineTranslation->suspend();
            $sender = [
                'name' => $this->escaper->escapeHtml($emailNameSender),
                'email' => $this->escaper->escapeHtml($emailSender),
            ];
            //Send Email
            $transport = $this->transportBuilder
                ->setTemplateIdentifier($emailTemplate)
                ->setTemplateOptions(
                    [
                        'area' => Area::AREA_FRONTEND,
                        'store' => Store::DEFAULT_STORE_ID,
                    ]
                )
                ->setTemplateVars($templateVar)
                ->setFrom($sender)
                ->addTo($receivers);
            $transport = $transport->getTransport();
            $transport->sendMessage();
            $this->inlineTranslation->resume();
        } catch (Exception $e) {
            $this->inlineTranslation->resume();
            $this->messageManager
                ->addErrorMessage(__('Failed to send email, please try again later.'.$e->getMessage()));
            return;
        }
    }
}
