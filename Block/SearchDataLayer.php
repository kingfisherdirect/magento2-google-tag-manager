<?php

namespace KingfisherDirect\GoogleTagManager\Block;

use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\Element\Template\Context;
use Magento\CatalogSearch\Block\Result;
use Magento\Search\Model\QueryFactory;

class SearchDataLayer extends AbstractBlock implements DataLayerConfiguratorInterface
{
    /**
     * @var QueryFactory
     */
    private $queryFactory;

    public function __construct(QueryFactory $queryFactory, Context $context, array $data = [])
    {
        $this->queryFactory = $queryFactory;
        parent::__construct($context, $data);
    }

    public function configureDataLayer(DataLayer $dataLayer)
    {
        $data = [
            'search-term' => $this->getSearchQuery(),
        ];

        $results = $this->getSearchResultCount();

        if ($results !== null) {
            $data['results'] = $results;
        }

        $dataLayer->push($data);
    }

    public function getSearchQuery()
    {
        return $this->queryFactory->get()->getQueryText();
    }

    public function getSearchResultCount()
    {
        $searchBlock = $this->getLayout()->getBlock('search.result');

        if (!($searchBlock instanceof Result)) {
            return null;
        }

        return $searchBlock->getResultCount();
    }
}
