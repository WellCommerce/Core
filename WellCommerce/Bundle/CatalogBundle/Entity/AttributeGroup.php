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

namespace WellCommerce\Bundle\CatalogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Knp\DoctrineBehaviors\Model\Blameable\Blameable;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use Knp\DoctrineBehaviors\Model\Translatable\Translatable;
use WellCommerce\Bundle\CoreBundle\Doctrine\Behaviours\Identifiable;
use WellCommerce\Bundle\CoreBundle\Entity\EntityInterface;

/**
 * Class AttributeGroup
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class AttributeGroup implements EntityInterface
{
    use Identifiable;
    use Translatable;
    use Timestampable;
    use Blameable;
    
    /**
     * @var Collection
     */
    protected $attributes;
    
    /**
     * AttributeGroup constructor.
     */
    public function __construct()
    {
        $this->attributes = new ArrayCollection();
    }
    
    public function getAttributes(): Collection
    {
        return $this->attributes;
    }
    
    public function setAttributes(Collection $attributes)
    {
        if ($this->attributes instanceof Collection) {
            $this->attributes->map(function (Attribute $attribute) use ($attributes) {
                if (false === $attributes->contains($attribute)) {
                    $this->removeAttribute($attribute);
                }
            });
        }
        
        $this->attributes = $attributes;
    }
    
    public function removeAttribute(Attribute $attribute)
    {
        $this->attributes->removeElement($attribute);
    }
    
    public function addAttribute(Attribute $attribute)
    {
        $this->attributes->add($attribute);
    }
    
    public function translate($locale = null, $fallbackToDefault = true): AttributeGroupTranslation
    {
        return $this->doTranslate($locale, $fallbackToDefault);
    }
}
