<?php

namespace KingfisherDirect\GoogleTagManager\Block;

use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\Element\Template\Context;
use Magento\Checkout\Model\Session;

class CheckoutSuccessDataLayer extends AbstractBlock implements DataLayerConfiguratorInterface
{
    /**
     * @var Session
     */
    private $checkoutSession;

    public function __construct(Context $context, array $data, Session $checkoutSession)
    {
        $this->checkoutSession = $checkoutSession;
        parent::__construct($context, $data);
    }

    public function configureDataLayer(DataLayer $dataLayer)
    {
        $order = $this->checkoutSession->getLastRealOrder();

        $orderDataLayer = [
            'ecommerce' => [
                'currencyCode' => $order->getOrderCurrencyCode(),
                'purchase' => [
                    'actionField' => [
                        'id' => $order->getIncrementId(),
                        'revenue' => $order->getGrandTotal(),
                        'tax' => $order->getTaxAmount(),
                        'shipping' => $order->getShippingAmount()
                    ],
                    'products' => []
                ]
            ]
        ];

        $productsToList = $order->getItems();

        foreach ($order->getItems() as $item) {
            $parent = $item->getParentItem();

            if (!$parent) {
                continue;
            }

            if (($key = array_search($parent, $productsToList)) === false) {
                continue;
            }

            unset($productsToList[$key]);
        }

        foreach ($productsToList as $item) {
            $parent = $item->getParentItem();

            $orderDataLayer['ecommerce']['purchase']['products'][] = [
                'id' => $item->getSku(),
                'name' => $item->getName(),
                'price' => $item->getPriceInclTax() ?? $parent->getPriceInclTax(),
                'quantity' => round($item->getQtyOrdered()),
            ];
        }

        $dataLayer->push(['ecommerce' => null ]);
        $dataLayer->push($orderDataLayer);
    }
}
