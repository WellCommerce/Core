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

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WellCommerce\Bundle\CatalogBundle\Entity\Product;
use WellCommerce\Bundle\CatalogBundle\Entity\Variant;
use WellCommerce\Bundle\CoreBundle\Controller\Front\AbstractFrontController;
use WellCommerce\Bundle\OrderBundle\Entity\OrderProduct;
use WellCommerce\Bundle\OrderBundle\Exception\AddCartItemException;
use WellCommerce\Bundle\OrderBundle\Manager\OrderProductManager;

/**
 * Class CartController
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class CartController extends AbstractFrontController
{
    /**
     * @var OrderProductManager
     */
    protected $manager;
    
    public function indexAction(): Response
    {
        $order = $this->getOrderProvider()->getCurrentOrder();
        $form  = $this->formBuilder->createForm($order, [
            'validation_groups' => ['cart'],
        ]);
        
        if ($form->handleRequest()->isSubmitted()) {
            if ($form->isValid()) {
                $this->manager->updateResource($order);
                
                return $this->getRouterHelper()->redirectTo('front.cart.index');
            }
            
            if (count($form->getError())) {
                $this->getFlashHelper()->addError('client.flash.registration.error');
            }
        }
        
        return $this->displayTemplate('index', [
            'form'     => $form,
            'order'    => $order,
            'elements' => $form->getChildren(),
        ]);
    }
    
    public function addAction(Product $product, Variant $variant = null, int $quantity = 1): Response
    {
        $variants         = $product->getVariants();
        $order            = $this->getOrderProvider()->getCurrentOrder();
        $previousQuantity = $this->manager->getCartQuantity($product, $variant, $order);
        
        if ($variants->count() && (null === $variant || false === $variants->contains($variant))) {
            return $this->redirectToRoute('front.product.view', ['id' => $product->getId()]);
        }
        
        try {
            $this->manager->addProductToOrder(
                $product,
                $variant,
                $quantity,
                $order
            );
        } catch (AddCartItemException $exception) {
            return $this->jsonResponse([
                'error' => $exception->getMessage(),
            ]);
        }
        
        $expectedQuantity = $previousQuantity + $quantity;
        $currentQuantity  = $this->manager->getCartQuantity($product, $variant, $order);
        $addedQuantity    = 0;
        if ($currentQuantity >= $expectedQuantity) {
            $addedQuantity = $currentQuantity - $previousQuantity;
        }
        
        $category        = $product->getCategories()->first();
        $recommendations = $this->get('product.helper')->getProductRecommendationsForCategory($category);
        
        $basketModalContent = $this->renderView('WellCommerceOrderBundle:Front/Cart:add.html.twig', [
            'product'          => $product,
            'order'            => $order,
            'recommendations'  => $recommendations,
            'previousQuantity' => $previousQuantity,
            'currentQuantity'  => $currentQuantity,
            'addedQuantity'    => $addedQuantity,
        ]);
        
        $cartPreviewContent = $this->renderView('WellCommerceOrderBundle:Front/Cart:preview.html.twig');
        
        return $this->jsonResponse([
            'basketModalContent' => $basketModalContent,
            'cartPreviewContent' => $cartPreviewContent,
            'templateData'       => [],
            'productTotal'       => [
                'quantity'    => $order->getProductTotal()->getQuantity(),
                'grossAmount' => $this->getCurrencyHelper()->convertAndFormat($order->getProductTotal()->getGrossPrice()),
            ],
        ]);
    }
    
    public function editAction(Request $request, OrderProduct $orderProduct, int $quantity): Response
    {
        $success = true;
        $message = null;
        $order   = $this->getOrderProvider()->getCurrentOrder();
        
        try {
            $this->manager->changeOrderProductQuantity(
                $orderProduct,
                $order,
                $quantity
            );
        } catch (\Exception $e) {
            $success = false;
            $message = $e->getMessage();
        }
        
        if ($request->isXmlHttpRequest()) {
            return $this->jsonResponse([
                'success' => $success,
                'message' => $message,
            ]);
        }
        
        return $this->redirectResponse($request->headers->get('referer'));
    }
    
    public function deleteAction(OrderProduct $orderProduct): Response
    {
        try {
            $this->manager->deleteOrderProduct(
                $orderProduct,
                $this->getOrderProvider()->getCurrentOrder()
            );
        } catch (\Exception $e) {
            $this->getFlashHelper()->addError($e->getMessage());
        }
        
        return $this->redirectToAction('index');
    }
}
