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

namespace WellCommerce\Bundle\AppBundle\Entity;

/**
 * Class ClientContactDetails
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ClientDetails
{
    protected $password              = '';
    protected $passwordConfirm       = null;
    protected $username              = '';
    protected $discount              = null;
    protected $conditionsAccepted    = false;
    protected $newsletterAccepted    = false;
    protected $resetPasswordHash     = null;
    protected $legacyPassword        = null;
    protected $legacyPasswordEncoder = null;
    
    public function getDiscount()
    {
        return $this->discount;
    }
    
    public function setDiscount($discount)
    {
        $this->discount = $discount;
    }
    
    public function getPassword(): string
    {
        return $this->password;
    }
    
    public function setPassword(string $password)
    {
        $this->password = $password;
    }
    
    public function resetPassword()
    {
        $this->password = null;
    }
    
    public function setHashedPassword(string $password)
    {
        $this->password = password_hash($password, PASSWORD_BCRYPT);
    }
    
    public function setPasswordConfirm($password)
    {
        $this->passwordConfirm = $password;
    }
    
    public function getPasswordConfirm()
    {
        return $this->passwordConfirm;
    }
    
    public function isPasswordConfirmed(): bool
    {
        return strlen($this->passwordConfirm) && password_verify($this->passwordConfirm, $this->password);
    }
    
    public function getUsername(): string
    {
        return $this->username;
    }
    
    public function setUsername(string $username)
    {
        $this->username = $username;
    }
    
    public function isConditionsAccepted(): bool
    {
        return $this->conditionsAccepted;
    }
    
    public function setConditionsAccepted(bool $conditionsAccepted)
    {
        $this->conditionsAccepted = $conditionsAccepted;
    }
    
    public function isNewsletterAccepted(): bool
    {
        return $this->newsletterAccepted;
    }
    
    public function setNewsletterAccepted(bool $newsletterAccepted)
    {
        $this->newsletterAccepted = $newsletterAccepted;
    }
    
    public function getResetPasswordHash()
    {
        return $this->resetPasswordHash;
    }
    
    public function setResetPasswordHash($resetPasswordHash)
    {
        $this->resetPasswordHash = $resetPasswordHash;
    }
    
    public function getLegacyPassword()
    {
        return $this->legacyPassword;
    }
    
    public function setLegacyPassword($legacyPassword)
    {
        $this->legacyPassword = $legacyPassword;
    }
    
    public function getLegacyPasswordEncoder()
    {
        return $this->legacyPasswordEncoder;
    }
    
    public function setLegacyPasswordEncoder($legacyPasswordEncoder)
    {
        $this->legacyPasswordEncoder = $legacyPasswordEncoder;
    }
}
