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
namespace WellCommerce\Bundle\AppBundle\Form\Front;

use WellCommerce\Bundle\CoreBundle\Form\AbstractFormBuilder;
use WellCommerce\Component\Form\Elements\FormInterface;

/**
 * Class ClientResetPasswordFormBuilder
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ClientResetPasswordFormBuilder extends AbstractFormBuilder
{
    public function getAlias(): string
    {
        return 'front.client_reset_password';
    }
    
    public function buildForm(FormInterface $form)
    {
        $form->addChild($this->getElement('text_field', [
            'name'  => '_username',
            'label' => 'client.label.username',
        ]));

        $form->addFilter($this->getFilter('no_code'));
        $form->addFilter($this->getFilter('trim'));
        $form->addFilter($this->getFilter('secure'));
    }
}
