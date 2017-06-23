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

namespace WellCommerce\Bundle\OrderBundle;

use Doctrine\Common\Collections\Collection;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use WellCommerce\Bundle\CoreBundle\HttpKernel\AbstractWellCommerceBundle;
use WellCommerce\Bundle\OrderBundle\DependencyInjection\Compiler;

/**
 * Class WellCommerceOrderBundle
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class WellCommerceOrderBundle extends AbstractWellCommerceBundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new Compiler\RegisterOrderVisitorPass());
        $container->addCompilerPass(new Compiler\RegisterOrderModifierPass());
        $container->addCompilerPass(new Compiler\RegisterPaymentProcessorPass());
        $container->addCompilerPass(new Compiler\RegisterShippingMethodCalculatorPass());
        $container->addCompilerPass(new Compiler\RegisterShippingMethodOptionsProviderPass());
    }
    
    public static function registerBundles(Collection $bundles, string $environment)
    {
        $bundles->add(new self());
    }
}
