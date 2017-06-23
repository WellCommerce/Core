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

namespace WellCommerce\Bundle\CatalogBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WellCommerce\Bundle\CatalogBundle\Entity\Category;
use WellCommerce\Bundle\CatalogBundle\Manager\CategoryManager;
use WellCommerce\Bundle\CatalogBundle\Request\Storage\CategoryStorageInterface;
use WellCommerce\Bundle\CoreBundle\Controller\Admin\AbstractAdminController;
use WellCommerce\Component\Form\Elements\FormInterface;

/**
 * Class CategoryController
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class CategoryController extends AbstractAdminController
{
    public function indexAction(): Response
    {
        $categories = $this->getManager()->getRepository()->getCollection();
        $tree       = $this->createCategoryTreeForm();
        
        if ($categories->count()) {
            $category = $categories->first();
            
            return $this->redirectToAction('edit', [
                'id' => $category->getId(),
            ]);
        }
        
        return $this->displayTemplate('index', [
            'tree' => $tree,
        ]);
    }
    
    public function addAction(Request $request): Response
    {
        if (!$request->isXmlHttpRequest()) {
            return $this->redirectToAction('index');
        }
        
        $categoriesName = (string)$request->request->get('name');
        $parentCategory = (int)$request->request->get('parent');
        $shop           = $this->getShopStorage()->getCurrentShop();
        $category       = $this->getManager()->quickAddCategory($categoriesName, $parentCategory, $shop);
        
        return $this->jsonResponse([
            'id' => $category->getId(),
        ]);
    }
    
    public function editAction(int $id): Response
    {
        $category = $this->getManager()->getRepository()->find($id);
        if (!$category instanceof Category) {
            return $this->redirectToAction('index');
        }
        
        $this->getCategoryStorage()->setCurrentCategory($category);
        $form = $this->formBuilder->createForm($category);
        
        if ($form->handleRequest()->isSubmitted()) {
            if ($form->isValid()) {
                $this->getManager()->updateResource($category);
            }
            
            return $this->createFormDefaultJsonResponse($form);
        }
        
        return $this->displayTemplate('edit', [
            'tree'     => $this->createCategoryTreeForm(),
            'form'     => $form,
            'resource' => $category,
        ]);
    }
    
    protected function createCategoryTreeForm(): FormInterface
    {
        return $this->get('category_tree.form_builder.admin')->createForm(null, [
            'class' => 'category-select',
        ]);
    }
    
    public function ajaxGetChildrenAction(Request $request): JsonResponse
    {
        $parentId = $request->request->get('parent');
        $parent   = $this->manager->getRepository()->find($parentId);
        $items    = $this->get('category.datagrid.filter')->getOptions($parent);
        
        return $this->jsonResponse($items);
    }
    
    protected function getManager(): CategoryManager
    {
        return parent::getManager();
    }
    
    protected function getCategoryStorage(): CategoryStorageInterface
    {
        return $this->get('category.storage');
    }
}
