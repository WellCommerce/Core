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

namespace WellCommerce\Bundle\OrderBundle\Tests\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use WellCommerce\Bundle\AppBundle\Entity\Client;
use WellCommerce\Bundle\AppBundle\Entity\ClientBillingAddress;
use WellCommerce\Bundle\AppBundle\Entity\ClientContactDetails;
use WellCommerce\Bundle\AppBundle\Entity\ClientDetails;
use WellCommerce\Bundle\AppBundle\Entity\ClientShippingAddress;
use WellCommerce\Bundle\AppBundle\Entity\Shop;
use WellCommerce\Bundle\CoreBundle\Test\Entity\AbstractEntityTestCase;
use WellCommerce\Bundle\CouponBundle\Entity\Coupon;
use WellCommerce\Bundle\OrderBundle\Entity\Order;
use WellCommerce\Bundle\OrderBundle\Entity\OrderProductTotal;
use WellCommerce\Bundle\OrderBundle\Entity\OrderSummary;
use WellCommerce\Bundle\OrderBundle\Entity\PaymentMethod;
use WellCommerce\Bundle\OrderBundle\Entity\ShippingMethod;

/**
 * Class OrderTest
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class OrderTest extends AbstractEntityTestCase
{
    protected function createEntity()
    {
        return new Order();
    }
    
    public function testOrderConfirmed()
    {
        $order = new Order();
        $this->assertFalse($order->isConfirmed());
    }
    
    public function testOrderConditionsAccepted()
    {
        $order = new Order();
        $this->assertFalse($order->isConditionsAccepted());
    }
    
    public function testOrderIssueInvoice()
    {
        $order = new Order();
        $this->assertFalse($order->isIssueInvoice());
    }
    
    public function providerTestAccessor()
    {
        $faker = $this->getFakerGenerator();
        
        return [
            ['number', $faker->randomDigitNotNull],
            ['currency', $faker->currencyCode],
            ['currencyRate', $faker->randomFloat(4)],
            ['shippingMethodOption', $faker->randomAscii],
            ['comment', $faker->randomAscii],
            ['products', new ArrayCollection()],
            ['productTotal', new OrderProductTotal()],
            ['summary', new OrderSummary()],
            ['clientDetails', new ClientDetails()],
            ['contactDetails', new ClientContactDetails()],
            ['billingAddress', new ClientBillingAddress()],
            ['shippingAddress', new ClientShippingAddress()],
            ['shop', new Shop()],
            ['shippingMethod', new ShippingMethod()],
            ['shippingMethod', null],
            ['paymentMethod', new PaymentMethod()],
            ['paymentMethod', null],
            ['client', new Client()],
            ['client', null],
            ['coupon', new Coupon()],
            ['createdAt', $faker->dateTime],
            ['updatedAt', $faker->dateTime],
        ];
    }
}
