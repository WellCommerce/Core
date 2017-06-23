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

namespace WellCommerce\Bundle\AppBundle\Controller\Box;

use Symfony\Component\HttpFoundation\Response;
use WellCommerce\Bundle\AppBundle\Entity\Client;
use WellCommerce\Bundle\CoreBundle\Controller\Box\AbstractBoxController;
use WellCommerce\Component\Layout\Collection\LayoutBoxSettingsCollection;

/**
 * Class ClientRegistrationBoxController
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ClientRegistrationBoxController extends AbstractBoxController
{
    public function indexAction(LayoutBoxSettingsCollection $boxSettings): Response
    {
        /** @var Client $resource */
        $resource = $this->getManager()->initResource();
        
        $form = $this->formBuilder->createForm($resource, [
            'validation_groups' => ['client_registration'],
        ]);
        
        if ($form->handleRequest()->isSubmitted()) {
            if ($form->isValid()) {
                $this->getManager()->createResource($resource);

                if ($resource->isEnabled()) {
                    $this->getFlashHelper()->addSuccess('client.flash.registration.success');
                } else {
                    $this->getFlashHelper()->addSuccess('client.flash.registration.pending');
                }

                return $this->getRouterHelper()->redirectTo('front.client.login');
            }
            
            $this->getFlashHelper()->addError('client.flash.registration.error');
        }
        
        return $this->displayTemplate('index', [
            'form'        => $form,
            'boxSettings' => $boxSettings,
        ]);
    }
}
