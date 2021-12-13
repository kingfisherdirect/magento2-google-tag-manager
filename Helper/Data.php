<?php

namespace KingfisherDirect\GoogleTagManager\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    public const XML_PATH_ENABLE = 'google/tagmanager/enable';

    public const XML_PATH_CONTAINER_ID = 'google/tagmanager/container_id';

    public function isEnabled($storeId = null): bool
    {
        $enabled = $this->scopeConfig->getValue(
            self::XML_PATH_ENABLE,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );

        if (!$enabled) {
            return false;
        }

        return null !== $this->getContainerId($storeId = null);
    }

    public function getContainerId($storeId = null): ?string
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_CONTAINER_ID,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
}
