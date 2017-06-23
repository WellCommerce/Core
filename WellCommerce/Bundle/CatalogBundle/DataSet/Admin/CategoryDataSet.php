<?php
/*
 * WellCommerce Open-Source E-Commerce Platform
 *
 * This file is part of the WellCommerce package.
 *
 * (c) Adam Piotrowski <adam@wellcommerce.org>
 *
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 */

namespace WellCommerce\Bundle\CatalogBundle\DataSet\Admin;

use Doctrine\ORM\QueryBuilder;
use WellCommerce\Bundle\CoreBundle\DataSet\AbstractDataSet;
use WellCommerce\Component\DataSet\Configurator\DataSetConfiguratorInterface;

/**
 * Class CategoryDataSet
 *
 * @author Adam Piotrowski <adam@wellcommerce.org>
 */
class CategoryDataSet extends AbstractDataSet
{
    public function getIdentifier(): string
    {
        return 'admin.category';
    }
    
    public function configureOptions(DataSetConfiguratorInterface $configurator)
    {
        $configurator->setColumns([
            'id'             => 'category.id',
            'hierarchy'      => 'category.hierarchy',
            'enabled'        => 'category.enabled',
            'parent'         => 'IDENTITY(category.parent)',
            'children_count' => 'category.childrenCount',
            'products_count' => 'category.productsCount',
            'name'           => 'category_translation.name',
            'slug'           => 'category_translation.slug',
            'shop'           => 'category_shops.id',
            'route'          => 'IDENTITY(category_translation.route)',
        ]);
    }
    
    protected function createQueryBuilder(): QueryBuilder
    {
        $queryBuilder = $this->repository->getQueryBuilder();
        $queryBuilder->groupBy('category.id');
        $queryBuilder->leftJoin('category.translations', 'category_translation');
        $queryBuilder->leftJoin('category.shops', 'category_shops');
        $queryBuilder->where($queryBuilder->expr()->eq('category_shops.id', $this->getShopStorage()->getCurrentShopIdentifier()));
        
        return $queryBuilder;
    }
}
