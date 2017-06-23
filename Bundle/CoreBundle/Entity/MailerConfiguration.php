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

namespace WellCommerce\Bundle\CoreBundle\Entity;

/**
 * Class MailerConfiguration
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class MailerConfiguration
{
    protected $from    = '';
    protected $host    = '';
    protected $port    = 587;
    protected $encrypt = 'tls';
    protected $user    = '';
    protected $pass    = '';
    protected $bcc     = '';
    
    public function getFrom(): string
    {
        return $this->from;
    }
    
    public function setFrom(string $from)
    {
        $this->from = $from;
    }
    
    public function getHost(): string
    {
        return $this->host;
    }
    
    public function setHost(string $host)
    {
        $this->host = $host;
    }
    
    public function getPort(): int
    {
        return $this->port;
    }
    
    public function setPort(int $port)
    {
        $this->port = $port;
    }
    
    public function getUser(): string
    {
        return $this->user;
    }
    
    public function setUser(string $user)
    {
        $this->user = $user;
    }
    
    public function getPass(): string
    {
        return $this->pass;
    }
    
    public function setPass(string $pass)
    {
        $this->pass = $pass;
    }
    
    public function getBcc(): string
    {
        return $this->bcc;
    }
    
    public function setBcc(string $bcc)
    {
        $this->bcc = $bcc;
    }
    
    public function getEncrypt()
    {
        return $this->encrypt;
    }
    
    public function setEncrypt(string $encrypt = null)
    {
        if (!in_array($encrypt, ['ssl', 'tls'])) {
            $encrypt = null;
        }
        
        $this->encrypt = $encrypt;
    }
}
