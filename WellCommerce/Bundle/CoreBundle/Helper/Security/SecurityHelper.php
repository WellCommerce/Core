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

namespace WellCommerce\Bundle\CoreBundle\Helper\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use WellCommerce\Bundle\AppBundle\Entity\Client;
use WellCommerce\Bundle\AppBundle\Entity\User;
use WellCommerce\Bundle\CoreBundle\Helper\Request\RequestHelperInterface;

/**
 * Class SecurityHelper
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class SecurityHelper implements SecurityHelperInterface
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;
    
    /**
     * @var RequestHelperInterface
     */
    private $requestHelper;
    
    /**
     * SecurityHelper constructor.
     *
     * @param TokenStorageInterface  $tokenStorage
     * @param RequestHelperInterface $requestHelper
     */
    public function __construct(TokenStorageInterface $tokenStorage, RequestHelperInterface $requestHelper)
    {
        $this->tokenStorage  = $tokenStorage;
        $this->requestHelper = $requestHelper;
    }
    
    public function getCurrentUser()
    {
        $token = $this->tokenStorage->getToken();
        if (null !== $token) {
            return $token->getUser();
        }
        
        return null;
    }
    
    public function getCurrentClient()
    {
        $user = $this->getCurrentUser();
        
        return $user instanceof Client ? $user : null;
    }
    
    public function getCurrentAdmin()
    {
        $user = $this->getCurrentUser();
        
        return $user instanceof User ? $user : null;
    }
    
    public function getAuthenticatedClient(): Client
    {
        return $this->getCurrentUser();
    }
    
    public function getAuthenticatedAdmin(): User
    {
        return $this->getCurrentUser();
    }
    
    public function isActiveFirewall(string $name): bool
    {
        $request = $this->requestHelper->getCurrentRequest();
        
        return $name === $this->getFirewallNameForRequest($request);
    }
    
    public function isActiveAdminFirewall(): bool
    {
        return $this->isActiveFirewall('admin');
    }
    
    public function getFirewallNameForRequest(Request $request)
    {
        list($mode,) = explode('/', ltrim($request->getPathInfo(), '/'));
        
        return ($mode === 'admin') ? 'admin' : 'client';
    }
    
    public function generateRandomPassword(int $length = 8): string
    {
        $chars    = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
        $password = substr(str_shuffle($chars), 0, $length);
        
        return $password;
    }
}
