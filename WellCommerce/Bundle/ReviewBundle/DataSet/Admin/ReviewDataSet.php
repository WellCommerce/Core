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

namespace WellCommerce\Bundle\ReviewBundle\DataSet\Admin;

use Doctrine\ORM\QueryBuilder;
use WellCommerce\Bundle\CoreBundle\DataSet\AbstractDataSet;
use WellCommerce\Component\DataSet\Configurator\DataSetConfiguratorInterface;

/**
 * Class ReviewDataSet
 *
 * @author Adam Piotrowski <adam@wellcommerce.org>
 */
class ReviewDataSet extends AbstractDataSet
{
    public function getIdentifier(): string
    {
        return 'admin.review';
    }
    
    public function configureOptions(DataSetConfiguratorInterface $configurator)
    {
        $configurator->setColumns([
            'id'        => 'reviews.id',
            'nick'      => 'reviews.nick',
            'enabled'   => 'reviews.enabled',
            'rating'    => 'reviews.rating',
            'review'    => 'reviews.review',
            'createdAt' => 'reviews.createdAt',
            'product'   => 'product_translation.name',
        ]);
        
        $configurator->setColumnTransformers([
            'createdAt' => $this->manager->createTransformer('date', ['format' => 'Y-m-d H:i:s']),
        ]);
    }
    
    protected function createQueryBuilder(): QueryBuilder
    {
        $queryBuilder = $this->repository->getQueryBuilder();
        $queryBuilder->groupBy('reviews.id');
        $queryBuilder->leftJoin('reviews.product', 'product_info');
        $queryBuilder->leftJoin('product_info.translations', 'product_translation');
        
        return $queryBuilder;
    }
}
