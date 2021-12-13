<?php

namespace KingfisherDirect\GoogleTagManager\Block;

use Magento\Backend\Block\Template\Context;
use Magento\Framework\App\Request\Http;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Store\Model\StoreManagerInterface;

class DefaultDataLayer extends AbstractBlock implements DataLayerConfiguratorInterface
{
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    protected $actionsSimplified = [
        'catalog_product_view' => 'product',
        'catalog_category_view' => 'category',
        'cms_index_index' => 'home',
        'contact_index_inded' => 'contact',
        'cms_page_view' => 'information',
        'catalogsearch_result_index' => 'search',
        'amblog_index_category' => 'blog',
        'amblog_index_post' => 'blog_post',
        'checkout_cart_index' => 'cart',
        'checkout_index_index' => 'checkout',
        'checkout_onepage_success' => 'checkout_success'
    ];

    public function __construct(Context $context, array $data)
    {
        $this->storeManager = $context->getStoreManager();
        $this->actionsSimplified = array_merge($this->actionsSimplified, $data['extraActionsSimplified'] ?? []);
        parent::__construct($context, $data);
    }

    public function configureDataLayer(DataLayer $dataLayer)
    {
        $dataLayer->push([
            'ecommerce' => [
                'currency' => $this->getStoreCurrencyCode(),
            ]
        ]);

        if ($pageType = $this->getPageTypeSimplified()) {
            $dataLayer->push([
                'page' => [
                    'type' => $pageType
                ]
            ]);
        }
    }

    public function getStoreCurrencyCode()
    {
        return $this->storeManager->getStore()->getCurrentCurrency()->getCode();
    }

    public function getPageTypeSimplified()
    {
        if (!$this->_request instanceof Http) {
            throw new \Exception("Unexpected request class");
        }

        $fullActionName = $this->_request->getFullActionName();

        return $this->actionsSimplified[$fullActionName] ?? null;
    }
}
