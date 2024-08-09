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

namespace  Dss\RefundRequest\Model\Plugin;

use Magento\Backend\App\Action\Context;
use Magento\Config\Model\Config\Factory;
use Magento\Framework\Stdlib\StringUtils;
use Magento\Config\Model\Config\Structure;
use Magento\Framework\Cache\FrontendInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Config\Controller\Adminhtml\System\Config\Save;
use Magento\Config\Controller\Adminhtml\System\ConfigSectionChecker;

class Validate extends Save
{
    /**
     * Save constructor.
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Config\Model\Config\Structure $configStructure
     * @param \Magento\Config\Controller\Adminhtml\System\ConfigSectionChecker $sectionChecker
     * @param \Magento\Config\Model\Config\Factory $configFactory
     * @param \Magento\Framework\Cache\FrontendInterface $cache
     * @param \Magento\Framework\Stdlib\StringUtils $string
     * @param \Magento\Framework\Controller\Result\RedirectFactory $redirectFactory
     */
    public function __construct(
        protected Context $context,
        protected Structure $configStructure,
        protected ConfigSectionChecker $sectionChecker,
        protected Factory $configFactory,
        protected FrontendInterface $cache,
        StringUtils $string,
        protected RedirectFactory $redirectFactory
    ) {
        parent::__construct(
            $context,
            $configStructure,
            $sectionChecker,
            $configFactory,
            $cache,
            $string
        );
    }

    /**
     * Validate Emails
     *
     * @param mixed $subject
     * @param mixed $proceed
     *
     * @return Redirect
     */
    public function aroundExecute($subject, $proceed): Redirect
    {
        $parameters = $this->getRequest()->getParam("groups");
        if (isset($parameters["dss_refundrequest_email_config"])) {
            $emails = '';
            if (isset($parameters["dss_refundrequest_email_config"]["fields"]["admin_email"]["value"])) {
                $emails = $parameters["dss_refundrequest_email_config"]["fields"]["admin_email"]["value"];
            }
            if ($emails != '') {
                $emailList = explode(",", $emails);
                $error = false;
                foreach ($emailList as $email) {
                    $checkEmail = trim($email);
                    if ($this->emailValidation($checkEmail)) {
                        $error = false;
                    } else {
                        $error = true;
                        break;
                    }
                }
                if ($error) {
                    $this->messageManager->addErrorMessage(__("One or more admin email has an invalid email format!"));
                    $resultRedirect = $this->resultRedirectFactory->create();
                    return $resultRedirect->setPath(
                        'adminhtml/system_config/edit',
                        [
                            '_current' => ['section', 'website', 'store'],
                            '_nosid' => true
                        ]
                    );
                } else {
                    return $proceed();
                }
            } else {
                return $proceed();
            }
        }
        return $proceed();
    }

    /**
     * Email validation
     *
     * @param mixed $email
     * @return bool
     */
    protected function emailValidation($email): bool
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        };
        return false;
    }
}
