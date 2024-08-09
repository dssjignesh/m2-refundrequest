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

use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Helper\AbstractHelper;

class Data extends AbstractHelper
{
    /**
     * Config for enable module
     * DSS_CONFIG_ENABLE_MODULE
     */
    protected const DSS_CONFIG_ENABLE_MODULE = 'dss_refundrequest/dss_refundrequest_general/enable';

    /**
     * Config for order refund
     * DSS_CONFIG_ORDER_REFUND
     */
    protected const DSS_CONFIG_ORDER_REFUND = 'dss_refundrequest/dss_refundrequest_general/canrefund';

    /**
     * Config for title popup
     * DSS_CONFIG_TITLE_POPUP
     */
    protected const DSS_CONFIG_POPUP_TITLE = 'dss_refundrequest/dss_refundrequest_config/popup_title';

    /**
     * Config for enable dropdown
     * DSS_CONFIG_ENABLE_DROPDOWN
     */
    protected const DSS_CONFIG_ENABLE_DROPDOWN = 'dss_refundrequest/dss_refundrequest_config/enable_dropdown';

    /**
     * Config for dropdown title
     * DSS_CONFIG_DROPDOWN_TITLE
     */
    protected const DSS_CONFIG_DROPDOWN_TITLE = 'dss_refundrequest/dss_refundrequest_config/dropdown_title';

    /**
     * Config for enable option
     * DSS_CONFIG_ENABLE_OPTION
     */
    protected const DSS_CONFIG_ENABLE_OPTION = 'dss_refundrequest/dss_refundrequest_config/enable_option';

    /**
     * Config for option title
     * DSS_CONFIG_OPTION_TITLE
     */
    protected const DSS_CONFIG_OPTION_TITLE = 'dss_refundrequest/dss_refundrequest_config/option_title';

    /**
     * Config for detail title
     * DSS_CONFIG_DETAIL_TITLE
     */
    protected const DSS_CONFIG_DETAIL_TITLE = 'dss_refundrequest/dss_refundrequest_config/detail_title';

    /**
     * Config for config title
     * DSS_CONFIG_TITLE
     */
    protected const DSS_CONFIG_POPUP_DESCRIPTION = 'dss_refundrequest/dss_refundrequest_config/popup_description';

    /**
     * Config for admin email
     * DSS_CONFIG_ADMIN_EMAIL
     */
    protected const DSS_CONFIG_ADMIN_EMAIL = 'dss_refundrequest/dss_refundrequest_email_config/admin_email';

    /**
     * Config for email template
     * DSS_CONFIG_EMAIL_TEMPLATE
     */
    protected const DSS_CONFIG_EMAIL_TEMPLATE = 'dss_refundrequest/dss_refundrequest_email_config/email_template';

    /**
     * Config for email sender
     * DSS_CONFIG_EMAIL_SENDER
     */
    protected const DSS_CONFIG_EMAIL_SENDER = 'dss_refundrequest/dss_refundrequest_email_config/email_sender';

    /**
     * Config for accept email
     * DSS_CONFIG_ACCEPT_EMAIL
     */
    protected const DSS_CONFIG_ACCEPT_EMAIL = 'dss_refundrequest/dss_refundrequest_email_config/accept_email';

    /**
     * Config for reject email
     * DSS_CONFIG_REJECT_EMAIL
     */
    protected const DSS_CONFIG_REJECT_EMAIL = 'dss_refundrequest/dss_refundrequest_email_config/reject_email';

    /**
     * Data constructor.
     *
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        protected Context $context
    ) {
        parent::__construct($context);
        $this->scopeConfig = $context->getScopeConfig();
    }

    /**
     * Get Config Enable Module
     *
     * @return string
     */
    public function getConfigEnableModule(): string
    {
        return $this->scopeConfig->getValue(
            self::DSS_CONFIG_ENABLE_MODULE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get Order Refund
     *
     * @return mixed
     */
    public function getOrderRefund(): mixed
    {
        return $this->scopeConfig->getValue(
            self::DSS_CONFIG_ORDER_REFUND,
            ScopeInterface::SCOPE_STORE
        );
    }
    /**
     * Get Config Title Module
     *
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->scopeConfig->getValue(
            self::DSS_CONFIG_POPUP_DESCRIPTION,
            ScopeInterface::SCOPE_STORE
        );
    }
    /**
     * Get Config Title Module
     *
     * @return string
     */
    public function getPopupModuleTitle(): string
    {
        return $this->scopeConfig->getValue(
            self::DSS_CONFIG_POPUP_TITLE,
            ScopeInterface::SCOPE_STORE
        );
    }
    /**
     * Get Config Enable Dropdown In Modal Popup
     *
     * @return string
     */
    public function getConfigEnableDropdown(): string
    {
        return $this->scopeConfig->getValue(
            self::DSS_CONFIG_ENABLE_DROPDOWN,
            ScopeInterface::SCOPE_STORE
        );
    }
    /**
     * Get Config Title Dropdown Modal Popup
     *
     * @return string
     */
    public function getDropdownTitle(): string
    {
        return $this->scopeConfig->getValue(
            self::DSS_CONFIG_DROPDOWN_TITLE,
            ScopeInterface::SCOPE_STORE
        );
    }
    /**
     * Get Config Enable Yes/No Option
     *
     * @return string
     */
    public function getConfigEnableOption(): string
    {
        return $this->scopeConfig->getValue(
            self::DSS_CONFIG_ENABLE_OPTION,
            ScopeInterface::SCOPE_STORE
        );
    }
    /**
     * Get Config Title Yes/No Option
     *
     * @return string
     */
    public function getOptionTitle(): string
    {
        return $this->scopeConfig->getValue(
            self::DSS_CONFIG_OPTION_TITLE,
            ScopeInterface::SCOPE_STORE
        );
    }
    /**
     * Get Config
     *
     * @return string
     */
    public function getDetailTitle(): string
    {
        return $this->scopeConfig->getValue(
            self::DSS_CONFIG_DETAIL_TITLE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get Admin Email
     *
     * @return mixed
     */
    public function getAdminEmail(): mixed
    {
        return $this->scopeConfig->getValue(
            self::DSS_CONFIG_ADMIN_EMAIL,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get Email Template
     *
     * @return string
     */
    public function getEmailTemplate(): string
    {
        return $this->scopeConfig->getValue(
            self::DSS_CONFIG_EMAIL_TEMPLATE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get Sender Email
     *
     * @return string
     */
    public function configSenderEmail(): mixed
    {
        return $this->scopeConfig->getValue(
            self::DSS_CONFIG_EMAIL_SENDER,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get Reject Email Tempalte
     *
     * @return string
     */
    public function getRejectEmailTemplate(): string
    {
        return $this->scopeConfig->getValue(
            self::DSS_CONFIG_REJECT_EMAIL,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get Accept Email Template
     *
     * @return string
     */
    public function getAcceptEmailTemplate(): string
    {
        return $this->scopeConfig->getValue(
            self::DSS_CONFIG_ACCEPT_EMAIL,
            ScopeInterface::SCOPE_STORE
        );
    }
}
