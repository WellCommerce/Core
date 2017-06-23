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

namespace WellCommerce\Bundle\OrderBundle\DataSet\Transformer;

use Doctrine\Common\Collections\Criteria;
use WellCommerce\Bundle\CoreBundle\Doctrine\Repository\RepositoryInterface;
use WellCommerce\Bundle\OrderBundle\Entity\OrderProduct;
use WellCommerce\Component\DataSet\Transformer\AbstractDataSetTransformer;

/**
 * Class OrderProductsTransformer
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class OrderProductsTransformer extends AbstractDataSetTransformer
{
    /**
     * @var RepositoryInterface
     */
    private $repository;
    
    /**
     * OrderProductsTransformer constructor.
     *
     * @param RepositoryInterface $repository
     */
    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }
    
    /**
     * {@inheritdoc}
     */
    public function transformValue($value)
    {
        $identifiers = explode(',', $value);
        $criteria    = new Criteria();
        $criteria->where($criteria->expr()->in('id', $identifiers));
        $orderProducts = $this->repository->matching($criteria);
        $lines         = [];
        
        $orderProducts->map(function (OrderProduct $orderProduct) use (&$lines) {
            $product  = $orderProduct->getProduct();
            $fullName = $product->translate()->getName();
            
            if ($orderProduct->hasVariant()) {
                foreach ($orderProduct->getOptions() as $name => $value) {
                    $fullName .= ' - ' . $value;
                }
            }
            
            $lines[] = sprintf('%s x %s', $orderProduct->getQuantity(), $fullName);
        });
        
        return implode('<br /><br />', $lines);
    }
}
