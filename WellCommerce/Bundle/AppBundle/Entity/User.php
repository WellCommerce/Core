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
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use WellCommerce\Bundle\CoreBundle\Doctrine\Behaviours\Enableable;
use WellCommerce\Bundle\CoreBundle\Doctrine\Behaviours\Identifiable;
use WellCommerce\Bundle\CoreBundle\Entity\EntityInterface;

/**
 * Class User
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class User implements \Serializable, EquatableInterface, UserInterface, EntityInterface
{
    use Identifiable;
    use Enableable;
    use Timestampable;
    use Blameable;
    
    protected $firstName = '';
    protected $lastName  = '';
    protected $username  = '';
    protected $apiKey    = '';
    protected $password  = '';
    protected $email     = '';
    
    /**
     * @var Collection
     */
    protected $roles;
    
    /**
     * @var Collection
     */
    protected $groups;
    
    public function __construct()
    {
        $this->roles  = new ArrayCollection();
        $this->groups = new ArrayCollection();
    }
    
    public function getFirstName(): string
    {
        return $this->firstName;
    }
    
    public function setFirstName(string $firstName)
    {
        $this->firstName = $firstName;
    }
    
    public function getLastName(): string
    {
        return $this->lastName;
    }
    
    public function setLastName(string $lastName)
    {
        $this->lastName = $lastName;
    }
    
    public function getFullName(): string
    {
        return sprintf('%s %s', $this->firstName, $this->lastName);
    }
    
    public function getUsername()
    {
        return $this->username;
    }
    
    public function setUsername($username)
    {
        $this->username = $username;
    }
    
    public function getEmail(): string
    {
        return $this->email;
    }
    
    public function setEmail(string $email)
    {
        $this->email = $email;
    }
    
    public function getSalt()
    {
        return null;
    }
    
    public function getPassword()
    {
        return $this->password;
    }
    
    public function setPassword($password)
    {
        if (strlen($password)) {
            $this->password = password_hash($password, PASSWORD_BCRYPT);
        }
    }
    
    public function getRoles()
    {
        return $this->roles->toArray();
    }
    
    public function addRole(Role $role)
    {
        $this->roles[] = $role;
    }
    
    public function setRoles(Collection $roles)
    {
        $this->roles = $roles;
    }
    
    public function eraseCredentials()
    {
    }
    
    public function serialize()
    {
        return serialize([$this->id, $this->username, $this->password]);
    }
    
    public function unserialize($serialized)
    {
        list($this->id, $this->username, $this->password) = unserialize($serialized);
    }
    
    public function isEqualTo(UserInterface $user)
    {
        if (!$user instanceof User) {
            return false;
        }
        
        if ($this->password !== $user->getPassword()) {
            return false;
        }
        
        if ($this->username !== $user->getUsername()) {
            return false;
        }
        
        return true;
    }
    
    public function getGroups(): Collection
    {
        return $this->groups;
    }
    
    public function setGroups(Collection $groups)
    {
        $this->groups = $groups;
    }
    
    public function getApiKey(): string
    {
        return $this->apiKey;
    }
    
    public function setApiKey(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }
}
