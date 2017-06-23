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

namespace WellCommerce\Bundle\AppBundle\Command\Package;

use WellCommerce\Bundle\CoreBundle\Helper\Package\PackageHelperInterface;

/**
 * Class UpdateCommand
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class UpdateCommand extends AbstractPackageCommand
{
    protected $composerOperation = PackageHelperInterface::ACTION_UPDATE;
    
    protected function configure()
    {
        parent::configure();
        $this->setDescription('Update WellCommerce package');
        $this->setName('wellcommerce:package:update');
    }
}
