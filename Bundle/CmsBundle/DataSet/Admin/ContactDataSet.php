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

namespace WellCommerce\Bundle\CmsBundle\DataSet\Admin;

use Doctrine\ORM\QueryBuilder;
use WellCommerce\Bundle\CoreBundle\DataSet\AbstractDataSet;
use WellCommerce\Component\DataSet\Configurator\DataSetConfiguratorInterface;

/**
 * Class ContactDataSet
 *
 * @author Adam Piotrowski <adam@wellcommerce.org>
 */
class ContactDataSet extends AbstractDataSet
{
    public function getIdentifier(): string
    {
        return 'admin.contact';
    }
    
    public function configureOptions(DataSetConfiguratorInterface $configurator)
    {
        $configurator->setColumns([
            'id'    => 'contact.id',
            'name'  => 'contact_translation.name',
            'topic' => 'contact_translation.topic',
        ]);
    }
    
    protected function createQueryBuilder(): QueryBuilder
    {
        $queryBuilder = $this->repository->getQueryBuilder();
        $queryBuilder->groupBy('contact.id');
        $queryBuilder->leftJoin('contact.translations', 'contact_translation');
        
        return $queryBuilder;
    }
}
