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

namespace WellCommerce\Bundle\CmsBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Knp\DoctrineBehaviors\Model\Blameable\Blameable;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use Knp\DoctrineBehaviors\Model\Translatable\Translatable;
use WellCommerce\Bundle\AppBundle\Entity\AddressTrait;
use WellCommerce\Bundle\AppBundle\Entity\ShopCollectionAwareTrait;
use WellCommerce\Bundle\CoreBundle\Doctrine\Behaviours\Enableable;
use WellCommerce\Bundle\CoreBundle\Doctrine\Behaviours\Identifiable;
use WellCommerce\Bundle\CoreBundle\Entity\EntityInterface;
use WellCommerce\Extra\CmsBundle\Entity\ContactExtraTrait;

/**
 * Class Contact
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class Contact implements EntityInterface
{
    use Identifiable;
    use Enableable;
    use Translatable;
    use Timestampable;
    use Blameable;
    use ShopCollectionAwareTrait;
    use AddressTrait;
    use ContactExtraTrait;
    
    public function __construct()
    {
        $this->shops = new ArrayCollection();
    }
    
    public function translate($locale = null, $fallbackToDefault = true): ContactTranslation
    {
        return $this->doTranslate($locale, $fallbackToDefault);
    }
}
