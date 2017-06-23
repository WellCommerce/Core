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

namespace WellCommerce\Bundle\CmsBundle\Form\Front;

use WellCommerce\Bundle\CoreBundle\Form\AbstractFormBuilder;
use WellCommerce\Component\Form\Elements\FormInterface;

/**
 * Class ContactFormBuilder
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ContactFormBuilder extends AbstractFormBuilder
{
    public function getAlias(): string
    {
        return 'front.contact';
    }
    
    public function buildForm(FormInterface $form)
    {
        $form->addChild($this->getElement('text_field', [
            'name'  => 'name',
            'label' => 'contact_ticket.label.name',
        ]));
        
        $form->addChild($this->getElement('text_field', [
            'name'  => 'surname',
            'label' => 'contact_ticket.label.surname',
        ]));
        
        $form->addChild($this->getElement('text_field', [
            'name'  => 'phone',
            'label' => 'contact_ticket.label.phone_number',
        ]));
        
        $form->addChild($this->getElement('text_field', [
            'name'  => 'email',
            'label' => 'contact_ticket.label.email',
        ]));
        
        $form->addChild($this->getElement('select', [
            'name'        => 'contact',
            'label'       => 'contact_ticket.label.contact',
            'options'     => $this->get('contact.dataset.front')->getResult('select', [], [
                'default_option' => '---',
                'label_column'   => 'topic',
            ]),
            'transformer' => $this->getRepositoryTransformer('entity', $this->get('contact.repository')),
        ]));
        
        $form->addChild($this->getElement('text_field', [
            'name'  => 'subject',
            'label' => 'contact_ticket.label.subject',
        ]));
        
        $form->addChild($this->getElement('text_area', [
            'name'  => 'content',
            'label' => 'contact_ticket.label.content',
            'rows'  => 5,
            'cols'  => 20,
        ]));
        
        $form->addFilter($this->getFilter('no_code'));
        $form->addFilter($this->getFilter('trim'));
        $form->addFilter($this->getFilter('secure'));
    }
}
