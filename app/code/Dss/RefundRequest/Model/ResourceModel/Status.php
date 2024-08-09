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

namespace Dss\RefundRequest\Model\ResourceModel;

use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class Status for update refund status
 */
class Status extends AbstractDb
{
    /**
     * Status constructor.
     *
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $datetime
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     */
    public function __construct(
        protected DateTime $datetime,
        protected Context $context
    ) {
        parent::__construct($context);
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('dss_refundrequest', 'increment_id');
    }

    /**
     * Update status and time in dss_refundrequest table
     *
     * @param array $incrementIds
     * @param int $refundStatus
     */
    public function updateStatusAndTime($incrementIds, $refundStatus)
    {
        $timeUpdate = $this->datetime->gmtDate('Y-m-d H:i:s');
        $connection = $this->getConnection();
        $where =  ['increment_id IN (?)' => $incrementIds];
        try {
            $connection->beginTransaction();
            $connection->update(
                $this->getMainTable(),
                ['updated_at' => $timeUpdate,'refund_status' => $refundStatus],
                $where
            );
            $connection->commit();
        } catch (\Exception $e) {
            $connection->rollBack();
        }
    }

    /**
     * Update refund status in sales_order_grid table
     *
     * @param array $incrementIds
     * @param int $status
     */
    public function updateOrderRefundStatus($incrementIds, $status)
    {
        $sales_order_grid = $this->getTable('sales_order_grid');
        $connection = $this->getConnection();
        $where =  ['increment_id IN (?)' => $incrementIds];
        try {
            $connection->beginTransaction();
            $connection->update(
                $sales_order_grid,
                ['refund_status' => $status],
                $where
            );
            $connection->commit();
        } catch (\Exception $e) {
            $connection->rollBack();
        }
    }
}
