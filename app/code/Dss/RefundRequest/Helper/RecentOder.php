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

use Dss\RefundRequest\Helper\Data;

class RecentOder
{
    protected const DSS_ORDER_RECENT_TEMPLATE = "Dss_RefundRequest::order/recent.phtml";
    protected const CORE_ORDER_RECENT_TEMPLATE = "Magento_Sales::order/recent.phtml";
    protected const DSS_ORDER_HISTORY_TEMPLATE = "Dss_RefundRequest::order/history.phtml";
    protected const CORE_ORDER_HISTORY_TEMPLATE = "Magento_Sales::order/history.phtml";

    /**
     * RecentOder constructor.
     *
     * @param \Dss\RefundRequest\Helper\Data $helperConfigAdmin
     */
    public function __construct(
        protected Data $helperConfigAdmin
    ) {
    }

    /**
     * Order Recent temaplate
     *
     * @return string
     */
    public function getTemplate(): string
    {
        if ($this->helperConfigAdmin->getConfigEnableModule()) {
            $template = self::ORDER_RECENT_TEMPLATE;
        } else {
            $template = self::CORE_ORDER_RECENT_TEMPLATE;
        }

        return $template;
    }

    /**
     * Order hitory tempalte
     *
     * @return string
     */
    public function getTemplateMyOder(): string
    {
        if ($this->helperConfigAdmin->getConfigEnableModule()) {
            $template = self::DSS_ORDER_HISTORY_TEMPLATE;
        } else {
            $template = self::CORE_ORDER_HISTORY_TEMPLATE;
        }
        return $template;
    }
}
