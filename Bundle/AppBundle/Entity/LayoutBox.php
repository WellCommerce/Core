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
use Doctrine\Common\Collections\Collection;
use Knp\DoctrineBehaviors\Model\Blameable\Blameable;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use Knp\DoctrineBehaviors\Model\Translatable\Translatable;
use WellCommerce\Bundle\CoreBundle\Doctrine\Behaviours\Identifiable;
use WellCommerce\Bundle\CoreBundle\Entity\EntityInterface;
use WellCommerce\Component\Layout\Model\LayoutBoxInterface;

/**
 * Class LayoutBox
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class LayoutBox implements EntityInterface, LayoutBoxInterface
{
    use Identifiable;
    use Timestampable;
    use Translatable;
    use Blameable;
    
    protected $boxType    = '';
    protected $identifier = '';
    protected $settings   = [];
    
    /**
     * @var Collection
     */
    protected $clientGroups;
    
    public function __construct()
    {
        $this->clientGroups = new ArrayCollection();
    }
    
    public function getBoxType(): string
    {
        return $this->boxType;
    }
    
    public function setBoxType(string $boxType)
    {
        $this->boxType = $boxType;
    }
    
    public function getSettings(): array
    {
        return $this->settings;
    }
    
    public function setSettings(array $settings)
    {
        $this->settings = $settings;
    }
    
    public function getIdentifier(): string
    {
        return $this->identifier;
    }
    
    public function setIdentifier(string $identifier)
    {
        $this->identifier = $identifier;
    }
    
    public function getBoxName(): string
    {
        return $this->translate()->getName();
    }
    
    public function getBoxContent(): string
    {
        return $this->translate()->getContent();
    }
    
    public function getClientGroups(): Collection
    {
        return $this->clientGroups;
    }
    
    public function setClientGroups(Collection $clientGroups)
    {
        $this->clientGroups = $clientGroups;
    }
    
    public function translate($locale = null, $fallbackToDefault = true): LayoutBoxTranslation
    {
        return $this->doTranslate($locale, $fallbackToDefault);
    }
}
