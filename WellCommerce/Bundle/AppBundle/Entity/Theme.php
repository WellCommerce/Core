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

namespace WellCommerce\Bundle\AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Knp\DoctrineBehaviors\Model\Blameable\Blameable;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use WellCommerce\Bundle\CoreBundle\Doctrine\Behaviours\Identifiable;
use WellCommerce\Bundle\CoreBundle\Entity\EntityInterface;

/**
 * Class Theme
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class Theme implements EntityInterface
{
    use Identifiable;
    use Timestampable;
    use Blameable;
    use ShopCollectionAwareTrait;
    
    protected $name   = '';
    protected $folder = '';
    
    public function __construct()
    {
        $this->shops = new ArrayCollection();
    }
    
    public function getName(): string
    {
        return $this->name;
    }
    
    public function setName(string $name)
    {
        $this->name = $name;
    }
    
    public function getFolder(): string
    {
        return $this->folder;
    }
    
    public function setFolder(string $folder)
    {
        $this->folder = $folder;
    }
}
