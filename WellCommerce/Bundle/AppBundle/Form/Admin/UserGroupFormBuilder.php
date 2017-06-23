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
namespace WellCommerce\Bundle\AppBundle\Form\Admin;

use WellCommerce\Bundle\CoreBundle\Form\AbstractFormBuilder;
use WellCommerce\Component\Form\Elements\FormInterface;

/**
 * Class UserGroupFormBuilder
 *
 * @author Adam Piotrowski <adam@wellcommerce.org>
 */
class UserGroupFormBuilder extends AbstractFormBuilder
{
    public function getAlias(): string
    {
        return 'admin.user_group';
    }
    
    public function buildForm(FormInterface $form)
    {
        $requiredData = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'required_data',
            'label' => 'common.fieldset.general'
        ]));

        $requiredData->addChild($this->getElement('text_field', [
            'name'  => 'name',
            'label' => 'common.label.name',
            'rules' => [
                $this->getRule('required')
            ],
        ]));

        $requiredData->addChild($this->getElement('rights_table', [
            'name'        => 'permissions',
            'label'       => 'user_group.label.permissions',
            'actions'     => $this->getPermissionActions(),
            'controllers' => $this->getPermissionTypes(),
            'transformer' => $this->getRepositoryTransformer('user_group_permission', $this->get('user_group.repository'))
        ]));

        $form->addFilter($this->getFilter('no_code'));
        $form->addFilter($this->getFilter('trim'));
        $form->addFilter($this->getFilter('secure'));
    }

    /**
     * Returns all routes which have require_permission option enabled
     *
     * @return array
     */
    private function getPermissionActions()
    {
        $actions = [];

        /**
         * @var $route \Symfony\Component\Routing\Route
         */
        foreach ($this->get('router')->getRouteCollection()->all() as $name => $route) {
            if ($route->hasOption('require_admin_permission')) {
                list(, $action) = explode('.', $route->getOption('require_admin_permission'));
                $actions[$action] = [
                    'id'   => $action,
                    'name' => $action
                ];
            }
        }

        ksort($actions);

        return array_values($actions);
    }

    /**
     * Returns all routes which have require_permission option enabled
     *
     * @return array
     */
    private function getPermissionTypes()
    {
        $types = [];

        /**
         * @var $route \Symfony\Component\Routing\Route
         */
        foreach ($this->get('router')->getRouteCollection()->all() as $name => $route) {
            if ($route->hasOption('require_admin_permission')) {
                list($permissionType,) = explode('.', $route->getOption('require_admin_permission'));
                $types[$permissionType] = [
                    'id'   => $permissionType,
                    'name' => $permissionType
                ];
            }
        }

        ksort($types);

        return array_values($types);
    }
}
