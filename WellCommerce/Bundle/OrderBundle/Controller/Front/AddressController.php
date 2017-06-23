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

namespace WellCommerce\Bundle\OrderBundle\Controller\Front;

use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use WellCommerce\Bundle\AppBundle\Entity\Client;
use WellCommerce\Bundle\CoreBundle\Controller\Front\AbstractFrontController;
use WellCommerce\Bundle\OrderBundle\Entity\Order;

/**
 * Class AddressController
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class AddressController extends AbstractFrontController
{
    public function indexAction(Request $request): Response
    {
        $order = $this->getOrderProvider()->getCurrentOrder();
        
        if (!$order->isConfirmable()) {
            return $this->redirectToRoute('front.cart.index');
        }
        
        $form = $this->formBuilder->createForm($order, [
            'validation_groups' => $this->getValidationGroupsForRequest($request),
        ]);
        
        if ($form->isSubmitted() && $this->isCopyBillingAddress($request)) {
            $this->copyShippingAddress($request->request);
        }
        
        if ($form->handleRequest()->isSubmitted()) {
            if ($form->isValid()) {
                if ($this->isCreateAccount($request)) {
                    $client = $this->autoRegisterClient($order);
                    $order->setClient($client);
                }
                $this->getManager()->updateResource($order);
                
                return $this->getRouterHelper()->redirectTo('front.confirm.index');
            }
            
            if (count($form->getError())) {
                $this->getFlashHelper()->addError('client.flash.registration.error');
            }
        }
        
        return $this->displayTemplate('index', [
            'form'     => $form,
            'elements' => $form->getChildren(),
        ]);
    }
    
    protected function getValidationGroupsForRequest(Request $request): array
    {
        $validationGroups = [
            'order_billing_address',
            'order_client_details',
            'order_contact_details',
        ];
        
        if (false === $this->isCopyBillingAddress($request)) {
            $validationGroups[] = 'order_shipping_address';
        }
        
        if ($this->isCreateAccount($request)) {
            $validationGroups[] = 'client_registration';
        }
        
        if ($this->isIssueInvoice($request)) {
            $validationGroups[] = 'order_issue_invoice';
        }
        
        return $validationGroups;
    }
    
    /**
     * Copies billing address to shipping address
     *
     * @param ParameterBag $parameters
     */
    protected function copyShippingAddress(ParameterBag $parameters)
    {
        $billingAddress = $parameters->get('billingAddress');
        
        $shippingAddress = [
            'shippingAddress.copyBillingAddress' => true,
        ];
        
        foreach ($billingAddress as $key => $value) {
            list(, $fieldName) = explode('.', $key);
            $shippingAddress['shippingAddress.' . $fieldName] = $value;
        }
        
        $parameters->set('shippingAddress', $shippingAddress);
    }
    
    protected function isCopyBillingAddress(Request $request): bool
    {
        if ($request->isMethod('POST')) {
            $shippingAddress = $request->request->filter('shippingAddress');
            
            return 1 === (int)$shippingAddress['shippingAddress.copyBillingAddress'];
        }
        
        return false;
    }
    
    protected function isCreateAccount(Request $request): bool
    {
        if ($request->isMethod('POST')) {
            $clientDetails = $request->request->filter('clientDetails');
            $createAccount = $clientDetails['clientDetails.createAccount'] ?? 0;
            
            return 1 === (int)$createAccount;
        }
        
        return false;
    }
    
    protected function isIssueInvoice(Request $request): bool
    {
        if ($request->isMethod('POST')) {
            return 1 === (int)$request->request->filter('issueInvoice');
        }
        
        return false;
    }
    
    protected function autoRegisterClient(Order $order): Client
    {
        /** @var $client Client */
        $client = $this->get('client.manager')->initResource();
        $client->setClientDetails($order->getClientDetails());
        $client->setContactDetails($order->getContactDetails());
        $client->setBillingAddress($order->getBillingAddress());
        $client->setShippingAddress($order->getShippingAddress());
        
        $this->get('client.manager')->createResource($client);
        
        $token = new UsernamePasswordToken($client, $client->getPassword(), "client", $client->getRoles());
        $this->get('security.token_storage')->setToken($token);
        
        $event = new InteractiveLoginEvent($this->getRequestHelper()->getCurrentRequest(), $token);
        $this->get("event_dispatcher")->dispatch('security.interactive_login', $event);
        
        return $client;
    }
}
