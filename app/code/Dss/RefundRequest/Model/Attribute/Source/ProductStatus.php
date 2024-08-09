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

class ProductStatus implements OptionSourceInterface
{
    protected const YES = 1;
    protected const NO = 0;

    /**
     * To Option Array
     *
     * @return array
     */
    public function toOptionArray(): array
    {
        return [
            ['value' => self::YES,  'label' => __('Yes')],
            ['value' => self::NO,  'label' => __('No')]
        ];
    }
}
