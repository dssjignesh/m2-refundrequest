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
namespace Dss\RefundRequest\Block;

use Dss\RefundRequest\Helper\Data;
use Magento\Sales\Block\Order\History;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\View\Element\Template;
use Magento\Customer\Model\Session as CustomerSession;
use Dss\RefundRequest\Model\ResourceModel\Request\Collection;
use Dss\RefundRequest\Model\ResourceModel\Label\Collection as LabelCollection;
use Dss\RefundRequest\Model\ResourceModel\Label\CollectionFactory;
use Magento\Sales\Model\ResourceModel\Order\Collection as SaleCollection;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory as OrderCollection;
use Dss\RefundRequest\Model\ResourceModel\Request\CollectionFactory as RequestCollectionFactory;

class Label extends Template
{
    /**
     * Label constructor.
     *
     * @param \Dss\RefundRequest\Helper\Data $helper
     * @param \Magento\Sales\Block\Order\History $history
     * @param \Dss\RefundRequest\Model\ResourceModel\Label\CollectionFactory $collectionFactory
     * @param \Dss\RefundRequest\Model\ResourceModel\Request\CollectionFactory $requestCollectionFactory
     * @param \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory
     * @param \Magento\Backend\Block\Template\Context $context
     * @param Magento\Customer\Model\Session $customerSession
     * @param array $data
     */
    public function __construct(
        protected Data $helper,
        protected History $history,
        protected CollectionFactory $collectionFactory,
        protected RequestCollectionFactory $requestCollectionFactory,
        protected OrderCollection $orderCollectionFactory,
        private Context $context,
        protected CustomerSession $customerSession,
        array $data = []
    ) {
        $this->formKey = $context->getFormKey();
        parent::__construct($context, $data);
    }

    /**
     * Construct
     */
    protected function _construct()
    {
        parent::_construct();
        $this->pageConfig->getTitle()->set(__('My Account'));
    }

    /**
     * Configuration module enable
     *
     * @return string
     */
    public function getConfigEnableModule(): string
    {
        return $this->helper->getConfigEnableModule();
    }

    /**
     * Get Popup Description
     *
     * @return null|string
     */
    public function getPopupDescription(): ?string
    {
        return $this->helper->getDescription();
    }

    /**
     * Is Dropdown enable
     *
     * @return string
     */
    public function getConfigEnableDropdown(): string
    {
        return $this->helper->getConfigEnableDropdown();
    }

    /**
     * DropDown Title
     *
     * @return string
     */
    public function getDropdownTitle(): string
    {
        return $this->helper->getDropdownTitle();
    }

    /**
     * Is Option Enable
     *
     * @return string
     */
    public function getConfigEnableOption(): string
    {
        return $this->helper->getConfigEnableOption();
    }

    /**
     * Get Option Title
     *
     * @return string
     */
    public function getOptionTitle(): string
    {
        return $this->helper->getOptionTitle();
    }

    /**
     * Get Detail Title
     *
     * @return string
     */
    public function getDetailTitle(): string
    {
        return $this->helper->getDetailTitle();
    }

    /**
     * Popup Title
     *
     * @return string
     */
    public function getPopupModuleTitle(): string
    {
        return $this->helper->getPopupModuleTitle();
    }

    /**
     * Get Order Refund
     *
     * @return mixed
     */
    public function getOrderRefund(): mixed
    {
        return $this->helper->getOrderRefund();
    }

    /**
     * Get Refund Status
     *
     * @return Collection
     */
    public function getRefundStatus(): Collection
    {
        $refundCollection = $this->requestCollectionFactory->create();
        $refundCollection->addFieldToSelect(['refund_status', 'increment_id']);
        return $refundCollection;
    }

    /**
     * Get status label
     *
     * @return \Dss\RefundRequest\Model\ResourceModel\Label\Collection
     */
    public function getLabel(): LabelCollection
    {
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('status', 0);
        return $collection;
    }

    /**
     * Get Order
     *
     * @return bool|\Magento\Sales\Model\ResourceModel\Order\Collection
     */
    public function getOrder(): bool|SaleCollection
    {
        return $this->history->getOrders();
    }

    /**
     * Get Form Key
     *
     * @return string
     */
    public function getFormKey(): string
    {
        return $this->formKey->getFormKey();
    }

    /**
     * Order Collection From Customer ID
     *
     * @return array
     */
    public function getOrderCollectionByCustomerId(): array
    {
        $customerId = $this->customerSession->getCustomer()->getId();
        $collection = $orders = [];

        if ($customerId) {
            $collection = $this->orderCollectionFactory->create()->addFieldToSelect(
                '*'
            )->addFieldToFilter(
                'customer_id',
                $customerId
            )->setOrder(
                'created_at',
                'desc'
            );
        }

        if (!empty($collection)) {
            foreach ($collection as $order) {
                $orders[] = [
                    "increment_id" => $order->getIncrementId(),
                    "status" => $order->getStatus()
                ];
            }
        }

        return $orders;
    }
}
