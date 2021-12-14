<?php

namespace KingfisherDirect\GoogleTagManager\Block;

use KingfisherDirect\GoogleTagManager\Helper\Data;
use Magento\Backend\Block\Template\Context;

class DataLayer extends Block
{
    /**
     * @var array
     */
    protected $pushes = [];

    public function __construct(
        Data $configuration,
        Context $context,
        array $data
    ) {
        parent::__construct($configuration, $context, $data);
    }

    public function push(array $data)
    {
        $this->pushes[] = $data;
    }

    public function getPushesSerialized()
    {
        return json_encode($this->pushes);
    }

    protected function _beforeToHtml()
    {
        foreach ($this->getChildNames() as $childName) {
            $childBlock = $this->getChildBlock($childName);

            $childBlock->configureDataLayer($this);
        }

        return $this;
    }

    public function getCacheKeyInfo()
    {
        $cacheInfo = parent::getCacheKeyInfo();
        $cacheInfo["action_name"] = $this->_request->getFullActionName();

        return $cacheInfo;
    }
}
