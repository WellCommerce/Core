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

namespace WellCommerce\Bundle\CatalogBundle\Form\DataTransformer;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\PropertyAccess\PropertyPathInterface;
use WellCommerce\Bundle\AppBundle\Entity\Media;
use WellCommerce\Bundle\AppBundle\Form\DataTransformer\MediaCollectionToArrayTransformer;
use WellCommerce\Bundle\CatalogBundle\Entity\Product;
use WellCommerce\Bundle\CatalogBundle\Entity\ProductPhoto;

/**
 * Class ProductPhotoCollectionToArrayTransformer
 *
 * @author Adam Piotrowski <adam@wellcommerce.org>
 */
class ProductPhotoCollectionToArrayTransformer extends MediaCollectionToArrayTransformer
{
    /**
     * {@inheritdoc}
     */
    public function transform($modelData)
    {
        if (null === $modelData || !$modelData instanceof PersistentCollection) {
            return [];
        }
        
        $items = [];
        foreach ($modelData as $item) {
            if ($item->isMainPhoto() == 1) {
                $items['main_photo'] = $item->getPhoto()->getId();
            }
            $items['photos'][] = $item->getPhoto()->getId();
        }
        
        return $items;
    }
    
    /**
     * {@inheritdoc}
     */
    public function reverseTransform($modelData, PropertyPathInterface $propertyPath, $values)
    {
        if (!$modelData instanceof Product) {
            throw new \InvalidArgumentException(sprintf('Wrong entity passed "%s"', get_class($modelData)));
        }
        
        if ($this->isPhotoCollectionUnModified($values)) {
            return false;
        }
        
        $previousCollection = $this->propertyAccessor->getValue($modelData, $propertyPath);
        $this->clearPreviousCollection($previousCollection);
        
        $collection = $this->createPhotosCollection($modelData, $values);
        
        if (0 === $collection->count()) {
            $modelData->setPhoto(null);
        }
        
        $this->propertyAccessor->setValue($modelData, $propertyPath, $collection);
    }
    
    /**
     * Checks whether photo collection was modified
     *
     * @param array $values
     *
     * @return bool
     */
    private function isPhotoCollectionUnModified($values)
    {
        return (isset($values['unmodified']) && (int)$values['unmodified'] === 1);
    }
    
    /**
     * Resets previous photo collection
     *
     * @param Collection $collection
     */
    protected function clearPreviousCollection(Collection $collection)
    {
        if ($collection->count()) {
            foreach ($collection as $item) {
                $collection->removeElement($item);
            }
        }
    }
    
    /**
     * Returns new photos collection
     *
     * @param Product $product
     * @param array   $values
     *
     * @return ArrayCollection
     */
    protected function createPhotosCollection(Product $product, $values)
    {
        $photos      = new ArrayCollection();
        $identifiers = $this->getMediaIdentifiers($values);
        $hierarchy   = 0;
        
        foreach ($identifiers as $id) {
            $media = $this->getMediaById($id);
            $photo = $this->getProductPhoto($media, $product, $values);
            $photo->setHierarchy($hierarchy++);
            if (!$photos->contains($photo)) {
                $photos->add($photo);
            }
        }
        
        return $photos;
    }
    
    /**
     * Fetch only media identifiers from an array
     *
     * @param array $values
     *
     * @return array
     */
    private function getMediaIdentifiers($values)
    {
        $identifiers = [];
        
        foreach ($values as $key => $id) {
            if (is_int($key)) {
                $identifiers[] = $id;
            }
        }
        
        return $identifiers;
    }
    
    /**
     * Returns media entity by its identifier
     *
     * @param int $id
     *
     * @return \WellCommerce\Bundle\AppBundle\Entity\Media
     */
    protected function getMediaById($id)
    {
        return $this->getRepository()->find($id);
    }
    
    protected function getProductPhoto(Media $media, Product $modelData, $values)
    {
        $mainPhoto    = $this->isMainPhoto($media, $values['main']);
        $productPhoto = new ProductPhoto();
        $productPhoto->setPhoto($media);
        $productPhoto->setMainPhoto($mainPhoto);
        $productPhoto->setProduct($modelData);
        
        if ($mainPhoto) {
            $modelData->setPhoto($media);
        }
        
        return $productPhoto;
    }
    
    private function isMainPhoto(Media $photo, $mainId): bool
    {
        return $photo->getId() === (int)$mainId;
    }
}
