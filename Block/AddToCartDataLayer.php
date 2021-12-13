<?php

namespace KingfisherDirect\GoogleTagManager\Block;

use Magento\Backend\Block\Template\Context;
use Magento\Framework\View\Element\Template;
use Magento\Store\Model\StoreManagerInterface;

class AddToCartDataLayer extends Template
{
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    public function __construct(Context $context, array $data)
    {
        $this->storeManager = $context->getStoreManager();
        parent::__construct($context, $data);
    }

    public function getStoreCurrencyCode()
    {
        return $this->storeManager->getStore()->getCurrentCurrency()->getCode();
    }
}
