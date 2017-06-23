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

namespace WellCommerce\Bundle\OrderBundle\Tests\Visitor;

use WellCommerce\Bundle\CatalogBundle\Entity\Product;
use WellCommerce\Bundle\CoreBundle\Test\AbstractTestCase;
use WellCommerce\Bundle\OrderBundle\Entity\Order;
use WellCommerce\Bundle\OrderBundle\Entity\OrderProduct;

/**
 * Class ConfirmationVisitorTest
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ConfirmationVisitorTest extends AbstractTestCase
{
    public function testValidOrderNumberIsGenerated()
    {
        $order     = new Order();
        $generator = $this->container->get('order.number_generator');
        $number    = $generator->generateOrderNumber($order);
        $visitor   = $this->container->get('confirmation.order.visitor');
        
        $this->assertNull($order->getNumber());
        
        $order->setConfirmed(true);
        
        $visitor->visitOrder($order);
        
        $this->assertEquals($order->getNumber(), $number);
    }
    
    public function testConfirmedOrderLocksProducts()
    {
        $visitor       = $this->container->get('confirmation.order.visitor');
        $order         = new Order();
        $orderProduct  = $this->createOrderProduct();
        $orderProducts = $order->getProducts();
        
        $orderProducts->add($orderProduct);
        
        $orderProducts->map(function (OrderProduct $orderProduct) {
            $this->assertFalse($orderProduct->isLocked());
        });
        
        $order->setConfirmed(true);
        
        $visitor->visitOrder($order);
        
        $order->getProducts()->map(function (OrderProduct $orderProduct) {
            $this->assertTrue($orderProduct->isLocked());
        });
    }
    
    public function testOrderHasNoPaymentsBeforeConfirmation()
    {
        $order = new Order();
        $this->assertEquals(0, $order->getPayments()->count());
    }
    
    private function createOrderProduct(): OrderProduct
    {
        /** @var Product $product */
        $product = $this->container->get('product.repository')->findOneBy([
            'enabled' => true,
        ]);
        
        $orderProduct = new OrderProduct();
        $orderProduct->setBuyPrice($product->getBuyPrice());
        $orderProduct->setSellPrice($product->getSellPrice());
        $orderProduct->setProduct($product);
        
        return $orderProduct;
    }
}
