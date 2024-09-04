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
namespace Dss\RefundRequest\Controller\Adminhtml\Request;

use Dss\RefundRequest\Helper\Data;
use Dss\RefundRequest\Helper\Email;
use Magento\Backend\App\Action\Context;
use Magento\Store\Model\ScopeInterface;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Locale\ListsInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Controller\ResultFactory;
use Dss\RefundRequest\Model\ResourceModel\Status;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Dss\RefundRequest\Model\ResourceModel\Request\CollectionFactory;

/** * @SuppressWarnings(PHPMD.CouplingBetweenObjects) */
class MassAccept extends \Magento\Backend\App\Action
{
    protected const STATUS_ACCEPT = 1;
    protected const ADMIN_REQUEST_MASSACCEPT = "Dss_RefundRequest::refundrequest_access_controller_request_massaccept";

    /**
     * MassAccept constructor.
     *
     * @param \Dss\RefundRequest\Helper\Email $emailSender
     * @param \Dss\RefundRequest\Helper\Data $helper
     * @param \Magento\Ui\Component\MassAction\Filter $filter
     * @param \Dss\RefundRequest\Model\ResourceModel\Request\CollectionFactory $collectionFactory
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone
     * @param \Magento\Framework\Locale\ListsInterface $localeLists
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Dss\RefundRequest\Model\ResourceModel\Status $statusResourceModel
     */
    public function __construct(
        protected Email $emailSender,
        protected Data $helper,
        protected Filter $filter,
        protected CollectionFactory $collectionFactory,
        protected ScopeConfigInterface $scopeConfig,
        protected TimezoneInterface $timezone,
        protected ListsInterface $localeLists,
        protected Context $context,
        protected Status $statusResourceModel
    ) {
        parent::__construct($context);
    }

    /**
     * Mass Accept refund request
     *
     * @return ResponseInterface|ResultInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        if ($this->helper->getConfigEnableModule()) {
            $acceptOrder = 0;
            $incrementIds = [];
            $collection = $this->filter->getCollection($this->collectionFactory->create());
            try {
                foreach ($collection as $key => $item) {
                    if ($item["refund_status"] != 1) {
                        $this->sendEmail($item);
                        $incrementIds[$key] = $item["increment_id"];
                        $acceptOrder++;
                    }
                }
                $this->statusResourceModel->updateOrderRefundStatus($incrementIds, self::STATUS_ACCEPT);
                $this->statusResourceModel->updateStatusAndTime($incrementIds, self::STATUS_ACCEPT);
                $this->messageManager->addSuccessMessage(__('%1 request has been accepted', $acceptOrder));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('*/*/');
            }
        } else {
            $this->messageManager->addWarningMessage(__('Module is disabled.'));
        }
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * Send Accept Email
     *
     * @param mixed $item
     * @return void
     */
    protected function sendEmail($item): void
    {
        $customerEmail = $item->getCustomerEmail();
        $timezone = $this->scopeConfig->getValue('general/locale/timezone', ScopeInterface::SCOPE_STORE);
        $date = $this->timezone->date();
        $timezoneLabel = $this->getTimezoneLabelByValue($timezone);
        $date = $date->format('Y-m-d h:i:s A')." ".$timezoneLabel;
        $emailTemplate = $this->helper->getAcceptEmailTemplate();
        $emailTemplateData = [
            'incrementId' => $item["increment_id"],
            'id' => $item["id"],
            'timeApproved'=> $date,
            'customerName' => $item["customer_name"]
        ];
        $this->emailSender->sendEmail($customerEmail, $emailTemplate, $emailTemplateData);
    }

    /**
     * Get timezone label
     *
     * @param mixed $timezoneValue
     * @return string
     */
    protected function getTimezoneLabelByValue($timezoneValue): string
    {
        $timezones = $this->localeLists->getOptionTimezones();
        $label = '';
        foreach ($timezones as $zone) {
            if ($zone["value"] == $timezoneValue) {
                $label = $zone["label"];
            }
        }
        return $label;
    }

    /**
     * Check Rule
     *
     * @return bool
     */
    protected function _isAllowed(): bool
    {
        return $this->_authorization
            ->isAllowed(self::ADMIN_REQUEST_MASSACCEPT);
    }
}
