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
namespace Dss\RefundRequest\Controller\Order;

use Exception;
use Dss\RefundRequest\Helper\Data;
use Dss\RefundRequest\Helper\Email;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Sales\Api\Data\OrderInterface;
use Dss\RefundRequest\Model\RequestFactory;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Data\Form\FormKey\Validator;

class Index extends Action
{
    /**
     * Index constructor.
     *
     * @param \Dss\RefundRequest\Helper\Email $emailSender
     * @param \Dss\RefundRequest\Helper\Data $helper
     * @param \Magento\Sales\Api\Data\OrderInterface $orderInterface
     * @param \Dss\RefundRequest\Model\RequestFactory $requestFactory
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator
     */
    public function __construct(
        protected Email $emailSender,
        protected Data $helper,
        protected OrderInterface $orderInterface,
        protected RequestFactory $requestFactory,
        protected Context $context,
        protected PageFactory $resultPageFactory,
        protected Validator $formKeyValidator
    ) {
        parent::__construct($context);
    }

    /**
     * Order refund request
     *
     * @return ResponseInterface|ResultInterface
     */
    public function execute(): ResponseInterface|ResultInterface
    {
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        if (!$this->formKeyValidator->validate($this->getRequest())) {
            $this->messageManager->addErrorMessage("Invalid request!");
            return $resultRedirect->setPath('customer/account/');
        }
        $model = $this->requestFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            if ($this->helper->getConfigEnableDropdown()) {
                $option = $data['dss-option'];
            } else {
                $option = '';
            }
            if ($this->helper->getConfigEnableOption()) {
                $radio = $data['dss-radio'];
            } else {
                $radio = '';
            }
            $reasonComment = $data['dss-refund-reason'];
            $incrementId   = $data['dss-refund-order-id'];
            $orderData     = $this->orderInterface->loadByIncrementId($incrementId);
            try {
                $model->setOption($option);
                $model->setRadio($radio);
                $model->setOrderId($incrementId);
                $model->setReasonComment($reasonComment);
                $model->setCustomerName($orderData->getCustomerName());
                $model->setCustomerEmail($orderData->getCustomerEmail());
                $model->save();
                try {
                    $this->sendEmail($orderData);
                    $this->messageManager
                        ->addSuccessMessage(__('Your refund request number #' . $incrementId . ' has been submited.'));
                } catch (Exception $e) {
                    $this->messageManager->addErrorMessage($e->getMessage());
                    return $resultRedirect->setPath('customer/account/');
                }
            } catch (Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('customer/account/');
            }
        }
        return $resultRedirect->setPath('customer/account/');
    }

    /**
     * Send Email for refund
     *
     * @param mixed $orderData
     * @return void
     */
    protected function sendEmail($orderData): void
    {
        $emailTemplate = $this->helper->getEmailTemplate();
        $adminEmail    = $this->helper->getAdminEmail();
        $adminEmails   = explode(",", $adminEmail);
        $countEmail    = count($adminEmails);
        if ($countEmail > 1) {
            foreach ($adminEmails as $value) {
                $value             = str_replace(' ', '', $value ?? '');
                $emailTemplateData = [
                    'adminEmail'   => $value,
                    'incrementId'  => $orderData->getIncrementId(),
                    'customerName' => $orderData->getCustomerName(),
                    'createdAt'    => $orderData->getCreatedAt(),
                ];
                $this->emailSender->sendEmail($value, $emailTemplate, $emailTemplateData);
            }
        } else {
            $emailTemplateData = [
                'adminEmail'   => $adminEmail,
                'incrementId'  => $orderData->getIncrementId(),
                'customerName' => $orderData->getCustomerName(),
                'createdAt'    => $orderData->getCreatedAt(),
            ];
            $this->emailSender->sendEmail($adminEmail, $emailTemplate, $emailTemplateData);
        }
    }
}
