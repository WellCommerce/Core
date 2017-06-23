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

namespace WellCommerce\Bundle\CmsBundle\Form\Admin;

use WellCommerce\Bundle\CoreBundle\Form\AbstractFormBuilder;
use WellCommerce\Component\Form\DataTransformer\DateTransformer;
use WellCommerce\Component\Form\Elements\FormInterface;

/**
 * Class NewsFormBuilder
 *
 * @author Adam Piotrowski <adam@wellcommerce.org>
 */
class NewsFormBuilder extends AbstractFormBuilder
{
    public function getAlias(): string
    {
        return 'admin.news';
    }
    
    public function buildForm(FormInterface $form)
    {
        $requiredData = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'required_data',
            'label' => 'common.fieldset.general',
        ]));
        
        $requiredData->addChild($this->getElement('checkbox', [
            'name'  => 'publish',
            'label' => 'common.label.publish',
        ]));
        
        $requiredData->addChild($this->getElement('checkbox', [
            'name'  => 'featured',
            'label' => 'common.label.featured',
        ]));
        
        $requiredData->addChild($this->getElement('date', [
            'name'        => 'startDate',
            'label'       => 'common.label.valid_from',
            'transformer' => new DateTransformer('m/d/Y'),
        ]));
        
        $requiredData->addChild($this->getElement('date', [
            'name'        => 'endDate',
            'label'       => 'common.label.valid_to',
            'transformer' => new DateTransformer('m/d/Y'),
        ]));
        
        $requiredData->addChild($this->getElement('select', [
            'name'        => 'category',
            'label'       => 'news.label.category',
            'options'     => $this->get('news_category.dataset.admin')->getResult('select', [], ['default_option' => '---']),
            'transformer' => $this->getRepositoryTransformer('entity', $this->get('news_category.repository')),
        ]));
        
        $languageData = $requiredData->addChild($this->getElement('language_fieldset', [
            'name'        => 'translations',
            'label'       => 'common.fieldset.translations',
            'transformer' => $this->getRepositoryTransformer('translation', $this->get('news.repository')),
        ]));
        
        $name = $languageData->addChild($this->getElement('text_field', [
            'name'  => 'topic',
            'label' => 'news.label.topic',
            'rules' => [
                $this->getRule('required'),
            ],
        ]));
        
        $languageData->addChild($this->getElement('slug_field', [
            'name'            => 'slug',
            'label'           => 'common.label.slug',
            'name_field'      => $name,
            'generate_route'  => 'route.generate',
            'translatable_id' => $this->getRequestHelper()->getAttributesBagParam('id'),
            'rules'           => [
                $this->getRule('required'),
            ],
        ]));
        
        $languageData->addChild($this->getElement('rich_text_editor', [
            'name'  => 'summary',
            'label' => 'news.label.summary',
        ]));
        
        $languageData->addChild($this->getElement('rich_text_editor', [
            'name'  => 'content',
            'label' => 'news.label.content',
        ]));
        
        $mediaData = $form->addChild($this->getElement('nested_fieldset', [
            'name'  => 'media_data',
            'label' => 'common.fieldset.photos',
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
        
        $this->addMetadataFieldset($form, $this->get('news.repository'));
        
        $this->addShopsFieldset($form);
        
        $form->addFilter($this->getFilter('trim'));
        $form->addFilter($this->getFilter('secure'));
    }
}
