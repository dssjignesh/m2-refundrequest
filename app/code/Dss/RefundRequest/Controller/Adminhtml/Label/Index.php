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
namespace Dss\RefundRequest\Controller\Adminhtml\Label;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\Page;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\ResultInterface;

class Index extends Action
{
    public const ADMIN_CONTROLLER_INDEX = "Dss_RefundRequest::refundrequest_access_controller_label_index";

    /**
     * Index constructor.
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        protected Context $context,
        protected PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
    }

    /**
     * Option grid
     *
     * @return ResponseInterface|ResultInterface|Page
     */
    public function execute(): ResponseInterface|ResultInterface|Page
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend((__(' Refund Request Dropdown Options')));
        return $resultPage;
    }

    /**
     * Check Rule
     *
     * @return bool
     */
    protected function _isAllowed(): bool
    {
        return $this->_authorization
            ->isAllowed(self::ADMIN_CONTROLLER_INDEX);
    }
}
