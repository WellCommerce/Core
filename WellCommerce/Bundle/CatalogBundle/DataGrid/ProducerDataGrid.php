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

namespace WellCommerce\Bundle\CatalogBundle\DataGrid;

use WellCommerce\Bundle\CoreBundle\DataGrid\AbstractDataGrid;
use WellCommerce\Component\DataGrid\Column\Column;
use WellCommerce\Component\DataGrid\Column\ColumnCollection;
use WellCommerce\Component\DataGrid\Column\Options\Appearance;
use WellCommerce\Component\DataGrid\Column\Options\Filter;
use WellCommerce\Component\DataGrid\Column\Options\Sorting;
use WellCommerce\Component\DataGrid\Configuration\EventHandler\UpdateRowEventHandler;
use WellCommerce\Component\DataGrid\Options\OptionsInterface;

/**
 * Class ProducerDataGrid
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ProducerDataGrid extends AbstractDataGrid
{
    public function configureColumns(ColumnCollection $collection)
    {
        $collection->add(new Column([
            'id'         => 'id',
            'caption'    => 'common.label.id',
            'sorting'    => new Sorting([
                'default_order' => Sorting::SORT_DIR_DESC,
            ]),
            'appearance' => new Appearance([
                'width'   => 90,
                'visible' => false,
            ]),
            'filter'     => new Filter([
                'type' => Filter::FILTER_BETWEEN,
            ]),
        ]));
        
        $collection->add(new Column([
            'id'      => 'name',
            'caption' => 'common.label.name',
        ]));
        
        $collection->add(new Column([
            'id'       => 'hierarchy',
            'caption'  => 'common.label.hierarchy',
            'editable' => true,
            'filter'   => new Filter([
                'type' => Filter::FILTER_BETWEEN,
            ]),
        ]));
    }
    
    public function configureOptions(OptionsInterface $options)
    {
        parent::configureOptions($options);
        
        $eventHandlers = $options->getEventHandlers();
        
        $eventHandlers->add(new UpdateRowEventHandler([
            'function' => $this->getJavascriptFunctionName('update'),
            'route'    => $this->getActionUrl('update'),
        ]));
    }
    
    public function getIdentifier(): string
    {
        return 'producer';
    }
}
