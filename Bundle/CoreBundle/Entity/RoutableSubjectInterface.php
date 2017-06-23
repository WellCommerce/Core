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

/**
 * Interface RoutableSubjectInterface
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
interface RoutableSubjectInterface
{
    public function getLocale();
    
    public function getTranslatable();
    
    public function getSlug(): string;
    
    public function getRoute();
    
    public function hasRoute(): bool;
    
    public function setRoute(Route $route);
    
    public function getRouteEntity(): Route;
}
