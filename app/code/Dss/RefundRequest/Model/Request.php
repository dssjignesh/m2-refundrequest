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
namespace Dss\RefundRequest\Model;

use Magento\Framework\Model\AbstractModel;
use Dss\RefundRequest\Model\ResourceModel\Request as ResourceModelRequest;

class Request extends AbstractModel
{
    /**
     * Define resource model
     */
    protected function _construct()
    {
        $this->_init(ResourceModelRequest::class);
    }

    /**
     * Set Order Id
     *
     * @param int $oderId
     */
    public function setOrderId($oderId)
    {
        $this->setData("increment_id", $oderId);
    }

    /**
     * Set Reason Comment
     *
     * @param mixed $reasonComment
     */
    public function setReasonComment($reasonComment)
    {
        $this->setData("reason_comment", $reasonComment);
    }

    /**
     * Set Time
     *
     * @param mixed $time
     */
    public function setTime($time)
    {
        $this->setData("create_at", $time);
    }

    /**
     * Set Option
     *
     * @param mixed $option
     */
    public function setOption($option)
    {
        $this->setData("reason_option", $option);
    }

    /**
     * Set Radio
     *
     * @param mixed $radio
     */
    public function setRadio($radio)
    {
        $this->setData("radio_option", $radio);
    }

    /**
     * Set Customer Name
     *
     * @param mixed $customerName
     */
    public function setCustomerName($customerName)
    {
        $this->setData("customer_name", $customerName);
    }

    /**
     * Set Customer Email
     *
     * @param mixed $customerEmail
     */
    public function setCustomerEmail($customerEmail)
    {
        $this->setData("customer_email", $customerEmail);
    }
}
