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

namespace WellCommerce\Bundle\AppBundle\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;

/**
 * Class LoginFailureHandler
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class LoginFailureHandler implements AuthenticationFailureHandlerInterface
{
    /**
     * @var RouterInterface
     */
    private $router;
    
    /**
     * @var string
     */
    private $loginRoute;
    
    public function __construct(RouterInterface $router, string $loginRoute)
    {
        $this->router     = $router;
        $this->loginRoute = $loginRoute;
    }
    
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);
        
        return new RedirectResponse($this->router->generate($this->loginRoute));
    }
}
