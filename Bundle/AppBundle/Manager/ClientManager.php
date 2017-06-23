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

namespace WellCommerce\Bundle\AppBundle\Manager;

use WellCommerce\Bundle\AppBundle\Entity\Client;
use WellCommerce\Bundle\AppBundle\Exception\NotFoundException;
use WellCommerce\Bundle\CoreBundle\Entity\EntityInterface;
use WellCommerce\Bundle\CoreBundle\Manager\AbstractManager;

/**
 * Class ClientManager
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ClientManager extends AbstractManager
{
    public function initResource(): EntityInterface
    {
        /** @var Client $client */
        $client = $this->factory->create();
        $shop   = $this->getShopStorage()->getCurrentShop();
        
        $client->setShop($shop);
        $client->getBillingAddress()->setCountry($shop->getDefaultCountry());
        $client->getShippingAddress()->setCountry($shop->getDefaultCountry());
        $client->setClientGroup($shop->getClientGroup());
        $client->setEnabled($shop->isEnableClient());
        
        $this->dispatchEvent(self::POST_ENTITY_INIT_EVENT, $client);
        
        return $client;
    }
    
    public function getClientByUsername(string $username): Client
    {
        $client = $this->getRepository()->findOneBy(['clientDetails.username' => $username]);
        
        if (!$client instanceof Client) {
            throw NotFoundException::user($username);
        }
        
        return $client;
    }
    
    public function resetPassword(Client $client)
    {
        $hash = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36) . $client->getId();
        $client->getClientDetails()->setResetPasswordHash($hash);
        $client->getClientDetails()->setHashedPassword($this->getSecurityHelper()->generateRandomPassword());
        $this->updateResource($client);
    }
}
