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
namespace Dss\RefundRequest\Block\Adminhtml\Label\Edit\Tab;

use Magento\Framework\Phrase;
use Magento\Framework\Registry;
use Magento\Cms\Model\Wysiwyg\Config;
use Magento\Framework\Data\FormFactory;
use Magento\Backend\Block\Template\Context;
use Magento\Config\Model\Config\Source\Yesno;
use \Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;

/**
 * Size Chart edit form main tab
 */
class Label extends Generic implements TabInterface
{
    /**
     * Label constructor.
     *
     * @param \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig
     * @param \Magento\Config\Model\Config\Source\Yesno $booleanOptions
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param array $data
     */
    public function __construct(
        protected Config $wysiwygConfig,
        protected Yesno $booleanOptions,
        protected Context $context,
        protected Registry $registry,
        protected FormFactory $formFactory,
        array $data = []
    ) {
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Refund request dropdown Option form
     *
     * @return Generic
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareForm(): Generic
    {
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('post_');
        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('Add New'), 'class' => 'fieldset-wide']
        );

        $fieldset->addField(
            'request_label',
            'text',
            [
                'label' => __('Option'),
                'title' => __('Option'),
                'name' => 'request_label',
                'required' => true,
            ]
        );
        $fieldset->addField(
            'status',
            'select',
            [
                'label' => __('Status'),
                'title' => __('Status'),
                'name' => 'status',
                'required' => false,
                'options' => ['0' => __('Enable'), '1' => __('Disable')]
            ]
        );
        $this->setForm($form);
        return parent::_prepareForm();
    }

    /**
     * Tab Label
     *
     * @return Phrase|string
     */
    public function getTabLabel(): Phrase|string
    {
        return __('Option');
    }

    /**
     * Tab Title
     *
     * @return Phrase|string
     */
    public function getTabTitle(): Phrase|string
    {
        return $this->getTabLabel();
    }

    /**
     * Show tab on form
     *
     * @return bool
     */
    public function canShowTab(): bool
    {
        return true;
    }

    /**
     * Is hidden
     *
     * @return bool
     */
    public function isHidden(): bool
    {
        return false;
    }
}
