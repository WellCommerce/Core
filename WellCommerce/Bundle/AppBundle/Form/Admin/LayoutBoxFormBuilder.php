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
 * Class LayoutBoxFormBuilder
 *
 * @author Adam Piotrowski <adam@wellcommerce.org>
 */
class LayoutBoxFormBuilder extends AbstractFormBuilder
{
    public function getAlias(): string
    {
        return 'admin.layout_box';
    }
    
    public function buildForm(FormInterface $form)
    {
        $requiredData = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'required_data',
            'label' => 'common.fieldset.general'
        ]));
        
        $languageData = $requiredData->addChild($this->getElement('language_fieldset', [
            'name'        => 'translations',
            'label'       => 'common.fieldset.translations',
            'transformer' => $this->getRepositoryTransformer('translation', $this->get('layout_box.repository'))
        ]));
        
        $languageData->addChild($this->getElement('text_field', [
            'name'  => 'name',
            'label' => 'layout_box.label.name',
            'rules' => [
                $this->getRule('required')
            ],
        ]));
    
        $languageData->addChild($this->getElement('rich_text_editor', [
            'name'  => 'content',
            'label' => 'layout_box.label.content'
        ]));
        
        $requiredData->addChild($this->getElement('text_field', [
            'name'    => 'identifier',
            'label'   => 'layout_box.label.identifier',
            'rules'   => [
                $this->getRule('required')
            ],
        ]));
        
        $requiredData->addChild($this->getElement('select', [
            'name'    => 'boxType',
            'label'   => 'layout_box.label.type',
            'default' => $this->getDefaultLayoutBoxType()
        ]));
    
        $requiredData->addChild($this->getElement('multi_select', [
            'name'        => 'clientGroups',
            'label'       => 'layout_box.label.client_groups',
            'options'     => $this->get('client_group.dataset.admin')->getResult('select'),
            'transformer' => $this->getRepositoryTransformer('collection', $this->get('client_group.repository')),
        ]));
        
        $form->addFilter($this->getFilter('no_code'));
        $form->addFilter($this->getFilter('trim'));
        $form->addFilter($this->getFilter('secure'));
    }
    
    private function getDefaultLayoutBoxType() : string
    {
        $type = $this->container->get('layout_box.configurator.collection')->first();
        
        return $type->getType();
    }
}
