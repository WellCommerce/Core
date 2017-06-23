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

namespace WellCommerce\Bundle\CmsBundle\Entity;

use Knp\DoctrineBehaviors\Model\Translatable\Translation;
use WellCommerce\Bundle\CoreBundle\Entity\AbstractTranslation;

/**
 * Class ContactTranslation
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ContactTranslation extends AbstractTranslation
{
    use Translation;
    
    protected $name          = '';
    protected $topic         = '';
    protected $email         = '';
    protected $phone         = '';
    protected $businessHours = '';
    
    public function getName(): string
    {
        return $this->name;
    }
    
    public function setName(string $name)
    {
        $this->name = $name;
    }
    
    public function getTopic(): string
    {
        return (string)$this->topic;
    }
    
    public function setTopic(string $topic)
    {
        $this->topic = $topic;
    }
    
    public function getEmail(): string
    {
        return $this->email;
    }
    
    public function setEmail(string $email)
    {
        $this->email = $email;
    }
    
    public function getPhone(): string
    {
        return $this->phone;
    }
    
    public function setPhone(string $phone)
    {
        $this->phone = $phone;
    }
    
    public function getBusinessHours(): string
    {
        return (string)$this->businessHours;
    }
    
    public function setBusinessHours(string $businessHours)
    {
        $this->businessHours = $businessHours;
    }
}
