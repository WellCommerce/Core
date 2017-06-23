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

namespace WellCommerce\Bundle\CatalogBundle\Tests\DependencyInjection;

use WellCommerce\Bundle\CoreBundle\Test\DependencyInjection\AbstractExtensionTestCase;

/**
 * Class ProductExtensionTest
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ProductExtensionTest extends AbstractExtensionTestCase
{
    /**
     * @return array
     */
    public function getRequiredServices(): array
    {
        return [
            'services' => [
                [
                    'product.repository',
                    'product.manager',
                    'product.form_builder.admin',
                    'product.dataset.admin',
                    'product.datagrid',
                    'product.controller.admin',
                ]
            ],
        ];
    }
}
