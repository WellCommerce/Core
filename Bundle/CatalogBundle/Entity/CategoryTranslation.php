<?php

namespace WellCommerce\Bundle\CatalogBundle\Entity;

use Knp\DoctrineBehaviors\Model\Translatable\Translation;
use WellCommerce\Bundle\AppBundle\Entity\Meta;
use WellCommerce\Bundle\CoreBundle\Entity\AbstractTranslation;
use WellCommerce\Bundle\CoreBundle\Entity\RoutableSubjectInterface;
use WellCommerce\Bundle\CoreBundle\Entity\RoutableTrait;
use WellCommerce\Bundle\CoreBundle\Entity\Route;
use WellCommerce\Extra\CatalogBundle\Entity\CategoryTranslationExtraTrait;

/**
 * Class CategoryTranslation
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class CategoryTranslation extends AbstractTranslation implements RoutableSubjectInterface
{
    use Translation;
    use RoutableTrait;
    use CategoryTranslationExtraTrait;
    
    protected $name             = '';
    protected $shortDescription = '';
    protected $description      = '';
    protected $meta;
    
    public function __construct()
    {
        $this->meta = new Meta();
    }
    
    public function getName(): string
    {
        return $this->name;
    }
    
    public function setName(string $name)
    {
        $this->name = $name;
    }
    
    public function getShortDescription(): string
    {
        return $this->shortDescription;
    }
    
    public function setShortDescription(string $shortDescription)
    {
        $this->shortDescription = $shortDescription;
    }
    
    public function getDescription(): string
    {
        return $this->description;
    }
    
    public function setDescription(string $description)
    {
        $this->description = $description;
    }
    
    public function getMeta(): Meta
    {
        return $this->meta;
    }
    
    public function setMeta(Meta $meta)
    {
        $this->meta = $meta;
    }
    
    public function getRouteEntity(): Route
    {
        return new CategoryRoute();
    }
}
