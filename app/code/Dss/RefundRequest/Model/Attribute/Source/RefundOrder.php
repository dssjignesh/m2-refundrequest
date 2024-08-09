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

use Dss\RefundRequest\Model\ResourceModel\Status;
use Magento\Framework\Data\OptionSourceInterface;
use Magento\Sales\Model\ResourceModel\Order\Status\Collection;
use Magento\Sales\Model\ResourceModel\Order\Status\CollectionFactory;

class RefundOrder implements OptionSourceInterface
{
    /**
     * RefundOrder constructor.
     *
     * @param \Magento\Sales\Model\ResourceModel\Order\Status\CollectionFactory $orderStatusCollection
     * @param \Dss\RefundRequest\Model\ResourceModel\Status $dssRefundStatus
     */
    public function __construct(
        protected CollectionFactory $orderStatusCollection,
        protected Status $dssRefundStatus
    ) {
    }

    /**
     * Get Order Status Lsit
     *
     * @return Collection
     */
    public function getStatus(): Collection
    {
        return $this->orderStatusCollection->create();
    }

    /**
     * Option Of Order Status
     *
     * @return array
     */
    public function toOptionArray():array
    {
        $array = [];
        foreach ($this->getStatus() as $value) {
            $array[] = ['value' => $value->getStatus(), 'label' => $value->getLabel()];
        }
        return $array;
    }
}
