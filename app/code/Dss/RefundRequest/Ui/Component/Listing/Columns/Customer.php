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

namespace Dss\RefundRequest\Ui\Component\Listing\Columns;

use Magento\Sales\Api\Data\OrderInterface;
use Magento\Framework\Filter\FilterManager;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;

/**
 * Class for Customer
 */
class Customer extends Column
{
    /**
     * Customer constructor.
     * @param \Magento\Sales\Api\Data\OrderInterface $orderInterface
     * @param \Magento\Framework\View\Element\UiComponent\ContextInterface $context
     * @param \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory
     * @param \Magento\Framework\Filter\FilterManager $filterManager
     * @param array $components
     * @param array $data
     */
    public function __construct(
        protected OrderInterface $orderInterface,
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        protected FilterManager $filterManager,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item['customer_name'])) {
                    $customerId = $this->getCustomerId($item);
                    $item['customer_name_url'] = $this->getLink($customerId);
                }
            }
        }
        return $dataSource;
    }

    /**
     * Get Url
     *
     * @param int $entityId
     * @return string
     */
    private function getLink($entityId): string
    {
        return $this->context->getUrl('customer/index/edit', ['id' => $entityId]);
    }

    /**
     * Get Customer Id
     *
     * @param mixed $item
     * @return mixed
     */
    private function getCustomerId($item): mixed
    {
        $incrementId = $item['increment_id'];
        $orderData = $this->orderInterface->loadByIncrementId($incrementId)->getCustomerId();
        return $orderData;
    }
}
