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

namespace WellCommerce\Bundle\CoreBundle\Manager;

use WellCommerce\Bundle\CoreBundle\Doctrine\Repository\RepositoryInterface;
use WellCommerce\Bundle\CoreBundle\Entity\EntityInterface;

/**
 * Interface ManagerInterface
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
interface ManagerInterface
{
    const POST_ENTITY_INIT_EVENT   = 'post_init';
    const PRE_ENTITY_UPDATE_EVENT  = 'pre_update';
    const POST_ENTITY_UPDATE_EVENT = 'post_update';
    const PRE_ENTITY_CREATE_EVENT  = 'pre_create';
    const POST_ENTITY_CREATE_EVENT = 'post_create';
    const PRE_ENTITY_REMOVE_EVENT  = 'pre_remove';
    const POST_ENTITY_REMOVE_EVENT = 'post_remove';
    
    /**
     * Returns the repository
     *
     * @return RepositoryInterface
     */
    public function getRepository(): RepositoryInterface;
    
    /**
     * Initializes new resource object
     *
     * @return EntityInterface
     */
    public function initResource();
    
    /**
     * Persists new resource
     *
     * @param EntityInterface $resource
     * @param bool            $flush
     */
    public function createResource(EntityInterface $resource, bool $flush = true);
    
    /**
     * Updates existing resource
     *
     * @param EntityInterface $resource
     * @param bool            $flush
     */
    public function updateResource(EntityInterface $resource, bool $flush = true);
    
    /**
     * Removes a resource
     *
     * @param EntityInterface $resource
     * @param bool            $flush
     */
    public function removeResource(EntityInterface $resource, bool $flush = true);
}
