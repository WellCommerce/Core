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

namespace WellCommerce\Bundle\CoreBundle\Entity;

use WellCommerce\Bundle\CoreBundle\Doctrine\Behaviours\Identifiable;
use WellCommerce\Bundle\CoreBundle\Entity\EntityInterface;

/**
 * Class Route
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class Route implements EntityInterface, RoutingDiscriminatorsAwareInterface
{
    use Identifiable;
    
    protected $path   = '';
    protected $locale = '';
    protected $identifier;
    
    public function getPath(): string
    {
        return $this->path;
    }
    
    public function setPath(string $path)
    {
        $this->path = $path;
    }
    
    public function getLocale(): string
    {
        return $this->locale;
    }
    
    public function setLocale(string $locale)
    {
        $this->locale = $locale;
    }
    
    public function getIdentifier()
    {
        return $this->identifier;
    }
    
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
    }
    
    public function getType(): string
    {
        return 'route';
    }
}
