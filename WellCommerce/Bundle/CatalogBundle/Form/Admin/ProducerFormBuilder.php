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
 * Class ProducerFormBuilder
 *
 * @author Adam Piotrowski <adam@wellcommerce.org>
 */
class ProducerFormBuilder extends AbstractFormBuilder
{
    public function getAlias(): string
    {
        return 'admin.producer';
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
            'transformer' => $this->getRepositoryTransformer('translation', $this->get('product.repository'))
        ]));

        $name = $languageData->addChild($this->getElement('text_field', [
            'name'  => 'name',
            'label' => 'common.label.name',
            'rules' => [
                $this->getRule('required')
            ],
        ]));

        $languageData->addChild($this->getElement('slug_field', [
            'name'            => 'slug',
            'label'           => 'common.label.slug',
            'name_field'      => $name,
            'generate_route'  => 'route.generate',
            'translatable_id' => $this->getRequestHelper()->getAttributesBagParam('id'),
            'rules'           => [
                $this->getRule('required')
            ],
        ]));
    
        $requiredData->addChild($this->getElement('checkbox', [
            'name'    => 'enabled',
            'label'   => 'common.label.enabled',
        ]));
        
        $requiredData->addChild($this->getElement('text_field', [
            'name'  => 'hierarchy',
            'label' => 'common.label.hierarchy',
            'rules' => [
                $this->getRule('required'),
            ],
        ]));
        
        $this->addMetadataFieldset($form, $this->get('producer.repository'));

        $mediaData = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'media_data',
            'label' => 'common.fieldset.photos'
        ]));

        $mediaData->addChild($this->getElement('image', [
            'name'         => 'photo',
            'label'        => 'form.media_data.image_id',
            'repeat_min'   => 0,
            'repeat_max'   => 1,
            'transformer'  => $this->getRepositoryTransformer('media_entity', $this->get('media.repository')),
            'session_id'   => $this->getRequestHelper()->getSessionId(),
            'session_name' => $this->getRequestHelper()->getSessionName(),
        ]));

        $delivererData = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'deliverers_data',
            'label' => 'producer.fieldset.deliverers'
        ]));

        $delivererData->addChild($this->getElement('multi_select', [
            'name'        => 'deliverers',
            'label'       => 'producer.label.deliverers',
            'options'     => $this->get('deliverer.dataset.admin')->getResult('select'),
            'transformer' => $this->getRepositoryTransformer('collection', $this->get('deliverer.repository'))
        ]));

        $this->addShopsFieldset($form);

        $form->addFilter($this->getFilter('no_code'));
        $form->addFilter($this->getFilter('trim'));
        $form->addFilter($this->getFilter('secure'));
    }
}
