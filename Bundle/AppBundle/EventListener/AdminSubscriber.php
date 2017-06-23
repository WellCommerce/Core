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
namespace WellCommerce\Bundle\AppBundle\EventListener;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use WellCommerce\Bundle\AppBundle\Entity\User;
use WellCommerce\Bundle\AppBundle\Repository\UserRepositoryInterface;
use WellCommerce\Bundle\CoreBundle\Doctrine\Event\EntityEvent;
use WellCommerce\Bundle\CoreBundle\EventListener\AbstractEventSubscriber;

/**
 * Class AdminSubscriber
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class AdminSubscriber extends AbstractEventSubscriber
{
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => ['onKernelController', 0],
            'user.pre_create'        => ['onUserPreCreate', 0],
        ];
    }
    
    public function onKernelController(FilterControllerEvent $event)
    {
        if ($this->getSecurityHelper()->isActiveFirewall('admin')) {
            $route = $this->getRouterHelper()->getCurrentRoute();
            if ($route->hasOption('require_admin_permission')) {
                $name       = $route->getOption('require_admin_permission');
                $user       = $this->getSecurityHelper()->getCurrentUser();
                $permission = $this->getUserRepository()->getUserPermission($name, $user);
                if (empty($permission)) {
                    $event->setController([$this->get('user.controller.admin'), 'accessDeniedAction']);
                }
            }
        }
    }
    
    public function onUserPreCreate(EntityEvent $entityEvent)
    {
        $password = $this->getSecurityHelper()->generateRandomPassword();
        $role     = $this->get('role.repository')->findOneByName('admin');
        $user     = $entityEvent->getEntity();
        if ($user instanceof User) {
            $user->addRole($role);
            $user->setPassword($password);
            
            $this->getMailerHelper()->sendEmail([
                'recipient'     => $user->getEmail(),
                'subject'       => $this->getTranslatorHelper()->trans('user.email.title.register'),
                'template'      => 'WellCommerceAppBundle:Admin/Email:register.html.twig',
                'parameters'    => [
                    'user'     => $user,
                    'password' => $password,
                ],
                'configuration' => $this->getShopStorage()->getCurrentShop()->getMailerConfiguration(),
            ]);
        }
    }
    
    private function getUserRepository(): UserRepositoryInterface
    {
        return $this->get('user.repository');
    }
}
