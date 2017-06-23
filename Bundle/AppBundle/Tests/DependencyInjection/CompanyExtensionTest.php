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

namespace WellCommerce\Bundle\AppBundle\Tests\DependencyInjection;

use WellCommerce\Bundle\CoreBundle\Test\DependencyInjection\AbstractExtensionTestCase;

/**
 * Class CompanyExtensionTest
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class CompanyExtensionTest extends AbstractExtensionTestCase
{
    /**
     * @return array
     */
    public function getRequiredServices(): array
    {
        return [
            'services' => [
                [
                    'company.repository',
                    'company.manager',
                    'company.form_builder.admin',
                    'company.dataset.admin',
                    'company.datagrid',
                    'company.controller.admin',
                ]
            ],
        ];
    }
}
