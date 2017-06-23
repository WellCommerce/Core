<?php
/**
 * WellCommerce Open-Source E-Commerce Platform
 *
 * This file is part of the WellCommerce package.
 *
 * (c) Adam Piotrowski <adam@wellcommerce.org>
 *
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 */

namespace WellCommerce\Bundle\WishlistBundle\Tests\Entity;

use WellCommerce\Bundle\AppBundle\Entity\Client;
use WellCommerce\Bundle\CatalogBundle\Entity\Product;
use WellCommerce\Bundle\CoreBundle\Test\Entity\AbstractEntityTestCase;
use WellCommerce\Bundle\WishlistBundle\Entity\Wishlist;

/**
 * Class WishlistTest
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class WishlistTest extends AbstractEntityTestCase
{
    protected function createEntity()
    {
        return new Wishlist();
    }
    
    public function providerTestAccessor()
    {
        $faker = $this->getFakerGenerator();
        
        return [
            ['client', new Client()],
            ['product', new Product()],
            ['createdAt', $faker->dateTime],
            ['updatedAt', $faker->dateTime],
        ];
    }
}
