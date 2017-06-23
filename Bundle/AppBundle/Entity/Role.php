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
use Symfony\Component\Security\Core\Role\RoleInterface;
use WellCommerce\Bundle\CoreBundle\Doctrine\Behaviours\Identifiable;
use WellCommerce\Bundle\CoreBundle\Entity\EntityInterface;

/**
 * Class Role
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class Role implements EntityInterface, RoleInterface
{
    use Identifiable;
    
    protected $name = '';
    protected $role = '';
    
    /**
     * @var Collection
     */
    protected $users;
    
    public function __construct()
    {
        $this->users = new ArrayCollection();
    }
    
    public function getRole(): string
    {
        return $this->role;
    }
    
    public function setRole(string $role)
    {
        $this->role = $role;
    }
    
    public function getName(): string
    {
        return $this->name;
    }
    
    public function setName(string $name)
    {
        $this->name = $name;
    }
    
    public function setUsers(Collection $users)
    {
        $this->users = $users;
    }
    
    public function getUsers(): Collection
    {
        return $this->users;
    }
}
