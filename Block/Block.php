<?php

namespace KingfisherDirect\GoogleTagManager\Block;

use KingfisherDirect\GoogleTagManager\Helper\Data;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\View\Element\Template;

class Block extends Template
{
    /**
     * @var Data
     */
    protected $configuration;

    public function __construct(Data $configuration, Context $context, array $data)
    {
        $this->configuration = $configuration;
        parent::__construct($context, $data);
    }

    public function getConfiguration(): Data
    {
        return $this->configuration;
    }

    protected function _toHtml()
    {
        if (!$this->configuration->isEnabled()) {
            return '';
        }

        return parent::_toHtml();
    }
}
