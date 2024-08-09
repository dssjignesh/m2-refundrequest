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
use Dss\RefundRequest\Helper\Data;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Dss\RefundRequest\Model\ResourceModel\Label\CollectionFactory;

class MassDisable extends Action
{
    public const ADMIN_CONTROLLER_MASSDISABLE = "Dss_RefundRequest::refundrequest_access_controller_label_massdisable";

    /**
     * MassDisable constructor.
     *
     * @param \Dss\RefundRequest\Helper\Data $helper
     * @param \Magento\Ui\Component\MassAction\Filter $filter
     * @param \Dss\RefundRequest\Model\ResourceModel\Label\CollectionFactory $collectionFactory
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        protected Data $helper,
        protected Filter $filter,
        protected CollectionFactory $collectionFactory,
        protected Context $context
    ) {
        parent::__construct($context);
    }

    /**
     * MassDelete action
     *
     * @return ResponseInterface|ResultInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute(): ResponseInterface|ResultInterface
    {
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        if ($this->helper->getConfigEnableModule()) {
            $setStatus = 0;
            $collection = $this->filter->getCollection($this->collectionFactory->create());
            try {
                foreach ($collection as $item) {
                    if ($item["status"] != 1) {
                        $this->setStatus($item);
                    }
                    $setStatus++;
                }
                $this->messageManager->addSuccessMessage(__('%1 option(s) has been disable.', $setStatus));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('*/*/');
            }
        } else {
            $this->messageManager->addWarningMessage(__('Module is disabled.'));
            return $resultRedirect->setPath('*/*/');
        }
    }

    /**
     * Set Refund Status
     *
     * @param mixed $item
     * @return void
     */
    protected function setStatus($item): void
    {
        $item->setData('status', 1);
        $item->save();
    }

    /**
     * Check Rule
     *
     * @return bool
     */
    protected function _isAllowed(): bool
    {
        return $this->_authorization
            ->isAllowed(self::ADMIN_CONTROLLER_MASSDISABLE);
    }
}
