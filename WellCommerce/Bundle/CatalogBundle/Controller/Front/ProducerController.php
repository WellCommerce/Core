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

namespace WellCommerce\Bundle\CatalogBundle\Controller\Front;

use Symfony\Component\HttpFoundation\Response;
use WellCommerce\Bundle\CatalogBundle\Entity\Producer;
use WellCommerce\Bundle\CoreBundle\Controller\Front\AbstractFrontController;
use WellCommerce\Component\Breadcrumb\Model\Breadcrumb;

/**
 * Class ProducerController
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ProducerController extends AbstractFrontController
{
    public function indexAction(Producer $producer): Response
    {
        $this->getBreadcrumbProvider()->add(new Breadcrumb([
            'label' => $producer->translate()->getName(),
        ]));
        
        $this->getProducerStorage()->setCurrentProducer($producer);
        
        $this->getMetadataHelper()->setMetadata($producer->translate()->getMeta());
        
        return $this->displayTemplate('index', [
            'producer' => $producer,
        ]);
    }
}
