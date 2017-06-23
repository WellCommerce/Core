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
namespace WellCommerce\Bundle\CatalogBundle\Repository;

use Doctrine\ORM\Query\Expr;
use WellCommerce\Bundle\CatalogBundle\Entity\Category;
use WellCommerce\Bundle\CoreBundle\Doctrine\Repository\EntityRepository;

/**
 * Class VariantOptionRepository
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class VariantOptionRepository extends EntityRepository implements VariantOptionRepositoryInterface
{
    public function getVariantOptionsForCategory(Category $category, string $locale)
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->select('attribute_translation.name AS attributeName, value_translation.name AS valueName, IDENTITY(variant_option.attributeValue) AS value');
        $queryBuilder->leftJoin('variant_option.variant', 'variants');
        $queryBuilder->leftJoin('variant_option.attribute', 'attributes');
        $queryBuilder->leftJoin('attributes.translations', 'attribute_translation', Expr\Join::WITH, 'attribute_translation.locale = :locale');
        $queryBuilder->leftJoin('variant_option.attributeValue', 'attribute_values');
        $queryBuilder->leftJoin('attribute_values.translations', 'value_translation', Expr\Join::WITH, 'value_translation.locale = :locale');
        $queryBuilder->leftJoin('variants.product', 'products');
        $queryBuilder->innerJoin('products.categories', 'categories', Expr\Join::WITH, 'categories.id = :category');
        $queryBuilder->groupBy('attribute_translation.name');
        $queryBuilder->addGroupBy('value_translation.name');
        $queryBuilder->addGroupBy('variant_option.attributeValue');
        $queryBuilder->setParameter('category', $category->getId());
        $queryBuilder->setParameter('locale', $locale);
        
        return $queryBuilder->getQuery()->getResult();
    }
}
