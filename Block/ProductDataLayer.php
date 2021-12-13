<?php

namespace KingfisherDirect\GoogleTagManager\Block;

use KingfisherDirect\GoogleTagManager\Block\DataLayerConfiguratorInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\Element\Template\Context;
use Magento\Catalog\Model\Product;
use Magento\ConfigurableProduct\Pricing\Price\FinalPriceResolver as ConfigurableFinalPriceResolver ;

class ProductDataLayer extends AbstractBlock implements DataLayerConfiguratorInterface
{
    /**
     * @var Registry
     */
    private $registry;

    /**
     * @var ConfigurablePriceResolverInterface
     */
    private $configurablePriceResolver;

    public function __construct(
        Registry $registry,
        ConfigurableFinalPriceResolver $configurablePriceResolver,
        Context $context,
        array $data = []
    ) {
        $this->registry = $registry;
        $this->configurablePriceResolver = $configurablePriceResolver;
        parent::__construct($context, $data);
    }

    public function configureDataLayer(DataLayer $dataLayer)
    {
        $product = $this->getProduct();

        if (!$product) {
            return null;
        }

        $dataLayer->push(['ecommerce' => null]);
        $dataLayer->push([
            'ecommerce' => [
                'detail' => [
                    'products' => [
                        [
                            'id' => $product->getSku(),
                            'name' => $product->getName(),
                            'price' => $this->getProductPrice($product),
                        ]
                    ]
                ]
            ]
        ]);
    }

    protected function getProduct()
    {
        return $this->registry->registry('current_product');
    }

    protected function getProductPrice(Product $product)
    {
        if ($product->getTypeId() === 'configurable') {
            return $this->configurablePriceResolver->resolvePrice($product);
        }

        return $product->getPrice();
    }
}
