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

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Framework\App\ResponseInterface;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Dss\RefundRequest\Model\ResourceModel\Request\CollectionFactory;

class MassDelete extends Action
{
    protected const ADMIN_REQUEST_DELETE = "Dss_RefundRequest::refundrequest_access_controller_request_massdelete";

    /**
     * Mass Delete Counstructor
     *
     * @param \Magento\Ui\Component\MassAction\Filter $filter
     * @param \Dss\RefundRequest\Model\ResourceModel\Request\CollectionFactory $collectionFactory
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        protected Filter $filter,
        protected CollectionFactory $collectionFactory,
        protected Context $context
    ) {
        parent::__construct($context);
    }

    /**
     * Massdelete action for refund request
     *
     * @return Redirect|ResponseInterface|ResultInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());

        $delete = 0;
        foreach ($collection as $item) {
            try {
                $this->deleteItem($item);
            } catch (\RuntimeException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while deleting.'));
            }
            $delete++;
        }
        $this->messageManager->addSuccessMessage(__('A total of %1 record(s) has been deleted.', $delete));
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * Delete refund requests
     *
     * @param mixed $item
     * @return mixed
     */
    protected function deleteItem($item): mixed
    {
        return $item->delete();
    }

    /**
     * Check Rule
     *
     * @return bool
     */
    protected function _isAllowed(): bool
    {
        return $this->_authorization
            ->isAllowed(self::ADMIN_REQUEST_DELETE);
    }
}
