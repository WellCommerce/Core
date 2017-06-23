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
namespace WellCommerce\Bundle\CatalogBundle\Form\Admin;

use WellCommerce\Bundle\CoreBundle\Form\AbstractFormBuilder;
use WellCommerce\Component\Form\Elements\FormInterface;

/**
 * Class AttributeValueFormBuilder
 *
 * @author Adam Piotrowski <adam@wellcommerce.org>
 */
class AttributeValueFormBuilder extends AbstractFormBuilder
{
    public function getAlias(): string
    {
        return 'admin.attribute_value';
    }
    
    public function buildForm(FormInterface $form)
    {
        $groupData = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'main_data',
            'label' => 'common.fieldset.general'
        ]));

        $languageData = $groupData->addChild($this->getElement('language_fieldset', [
            'name'        => 'translations',
            'label'       => 'common.fieldset.translations',
            'transformer' => $this->getRepositoryTransformer('translation', $this->get('attribute_value.repository'))
        ]));

        $languageData->addChild($this->getElement('text_field', [
            'name'  => 'name',
            'label' => 'common.label.name',
            'rules' => [
                $this->getRule('required')
            ],
        ]));

        $attributesData = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'attributes_data',
            'label' => 'attribute_value.fieldset.attributes'
        ]));

        $attributesData->addChild($this->getElement('multi_select', [
            'name'        => 'attributes',
            'label'       => 'attribute_value.label.attributes',
            'options'     => $this->get('attribute.dataset.admin')->getResult('select'),
            'transformer' => $this->getRepositoryTransformer('collection', $this->get('attribute.repository'))
        ]));

        $form->addFilter($this->getFilter('no_code'));
        $form->addFilter($this->getFilter('trim'));
        $form->addFilter($this->getFilter('secure'));
    }
}
