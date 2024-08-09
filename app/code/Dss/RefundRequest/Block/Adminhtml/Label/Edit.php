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

namespace Dss\RefundRequest\Block\Adminhtml\Label;

use \Magento\Framework\Registry;
use \Magento\Backend\Block\Widget;
use Magento\Backend\Block\Widget\Context;
use \Magento\Backend\Block\Widget\Form\Container;

class Edit extends Container
{
    /**
     * Edit constructor.
     *
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param array $data
     */
    public function __construct(
        protected Context $context,
        protected Registry $coreRegistry,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * Edit constructor.
     */
    protected function _construct()
    {
        $this->_objectId = 'id';
        $this->_blockGroup = 'Dss_RefundRequest';
        $this->_controller = 'adminhtml_label';
        parent::_construct();
        $this->buttonList->update('save', 'label', __('Save'));
        $this->buttonList->update('delete', 'label', __('Delete'));
    }

    /**
     * Get Header text
     *
     * @return \Magento\Framework\Phrase|string
     */
    public function getHeaderText(): Phrase|string
    {
        if ($this->coreRegistry->registry('dss_refundrequest')->getId()) {
            return __(
                "Edit '%1'",
                $this->escapeHtml(
                    $this->coreRegistry->registry('dss_refundrequest')->getTitle()
                )
            );
        } else {
            return __('Add New');
        }
    }
}
