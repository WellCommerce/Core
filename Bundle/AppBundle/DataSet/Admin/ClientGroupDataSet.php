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

namespace WellCommerce\Bundle\AppBundle\DataSet\Admin;

use Doctrine\ORM\QueryBuilder;
use WellCommerce\Bundle\CoreBundle\DataSet\AbstractDataSet;
use WellCommerce\Component\DataSet\Configurator\DataSetConfiguratorInterface;

/**
 * Class ClientGroupDataSet
 *
 * @author Adam Piotrowski <adam@wellcommerce.org>
 */
class ClientGroupDataSet extends AbstractDataSet
{
    public function getIdentifier(): string
    {
        return 'admin.client_group';
    }
    
    public function configureOptions(DataSetConfiguratorInterface $configurator)
    {
        $configurator->setColumns([
            'id'           => 'client_group.id',
            'name'         => 'client_group_translation.name',
            'description'  => 'client_group_translation.description',
            'totalClients' => 'COUNT(client)',
        ]);
    }
    
    protected function createQueryBuilder(): QueryBuilder
    {
        $queryBuilder = $this->repository->getQueryBuilder();
        $queryBuilder->groupBy('client_group.id');
        $queryBuilder->leftJoin('client_group.translations', 'client_group_translation');
        $queryBuilder->leftJoin('client_group.clients', 'client');
        
        return $queryBuilder;
    }
}
