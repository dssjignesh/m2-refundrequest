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

use Exception;
use RuntimeException;
use Magento\Framework\Registry;
use Magento\Backend\Model\Session;
use Magento\Framework\App\Action\Action;
use Dss\RefundRequest\Model\LabelFactory;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;

class Save extends Action
{
    protected const ADMIN_CONTROLLER_SAVE = "Dss_RefundRequest::refundrequest_access_controller_label_save";

    /**
     * Save constructor.
     * @param \Magento\Backend\Model\Session $backendSession
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Dss\RefundRequest\Model\LabelFactory $labelFactory
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        protected Session $backendSession,
        protected Registry $coreRegistry,
        protected LabelFactory $labelFactory,
        protected Context $context,
        protected PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
    }

    /**
     * Save Label
     *
     * @return ResponseInterface|ResultInterface
     */
    public function execute()
    {
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $model = $this->labelFactory->create();
        $data = $this->getRequest()->getPostValue();
        $model->setData($data);
        if ($data) {
            try {
                $model->save();
                $this->messageManager->addSuccessMessage(__('The option has been saved.'));
                $this->backendSession->setPostData(false);
                if ($this->getRequest()->getParam('back')) {
                    $resultRedirect->setPath('*/*/');
                    return $resultRedirect;
                }
                $resultRedirect->setPath('*/*/');
                return $resultRedirect;
            } catch (RuntimeException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving.'));
            }
            $this->_getSession()->setDssContactPostData($data);
            $resultRedirect->setPath('*/*/');
            return $resultRedirect;
        }
        $resultRedirect->setPath('*/*/');
        return $resultRedirect;
    }

    /**
     * Check Rule
     *
     * @return bool
     */
    protected function _isAllowed(): bool
    {
        return $this->_authorization
            ->isAllowed(self::ADMIN_CONTROLLER_SAVE);
    }
}
