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
namespace WellCommerce\Component\DataGrid\Event;

use Symfony\Component\EventDispatcher\Event;
use WellCommerce\Component\DataGrid\DataGridInterface;

/**
 * Class DataGridEvent
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class DataGridEvent extends Event
{
    const EVENT_SUFFIX = 'datagrid.post_init';
    
    /**
     * @var DataGridInterface
     */
    protected $datagrid;
    
    public function __construct(DataGridInterface $datagrid)
    {
        $this->datagrid = $datagrid;
    }
    
    public function getDataGrid(): DataGridInterface
    {
        return $this->datagrid;
    }
}
