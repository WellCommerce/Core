<?php

namespace WellCommerce\Bundle\AppBundle\Service\Metadata\Helper;

use WellCommerce\Bundle\AppBundle\Entity\Meta;
use WellCommerce\Bundle\AppBundle\Service\Shop\Storage\ShopStorageInterface;

/**
 * Class MetadataHelper
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class MetadataHelper implements MetadataHelperInterface
{
    /**
     * @var Meta
     */
    private $meta;
    
    /**
     * @var Meta
     */
    private $fallbackMeta;
    
    /**
     * @var ShopStorageInterface
     */
    private $shopStorage;
    
    public function __construct(ShopStorageInterface $shopStorage)
    {
        $this->shopStorage = $shopStorage;
    }
    
    public function setMetadata(Meta $meta)
    {
        $this->meta = $meta;
    }
    
    public function setFallbackMetadata(Meta $meta)
    {
        $this->fallbackMeta = $meta;
    }
    
    public function getMetadata(): Meta
    {
        if ($this->meta instanceof Meta && false === $this->meta->isEmpty()) {
            return $this->meta;
        }
        
        if ($this->fallbackMeta instanceof Meta) {
            return $this->fallbackMeta;
        }
        
        return $this->shopStorage->getCurrentShop()->translate()->getMeta();
    }
}
