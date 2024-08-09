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
namespace Dss\RefundRequest\Model\Attribute\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Dss\RefundRequest\Model\ResourceModel\Status as dssRefundStatus;

class Status implements OptionSourceInterface
{
    protected const PENDING = 0;
    protected const ACCEPT = 1;
    protected const REJECT = 2;
    protected const NA = null;

    /**
     * To Option Array
     *
     * @return array
     */
    public function toOptionArray(): array
    {
        return [
            ['value' => self::PENDING,  'label' => __('Pending')],
            ['value' => self::ACCEPT,  'label' => __('Accept')],
            ['value' => self::REJECT,  'label' => __('Reject')],
            ['value' => self::NA,  'label' => __('N/A')]
        ];
    }
}
