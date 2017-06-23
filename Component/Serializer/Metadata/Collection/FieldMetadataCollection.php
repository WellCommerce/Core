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

namespace WellCommerce\Component\Serializer\Metadata\Collection;

use WellCommerce\Component\Collections\ArrayCollection;
use WellCommerce\Component\Serializer\Metadata\FieldMetadataInterface;

/**
 * Class FieldMetadataCollection
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class FieldMetadataCollection extends ArrayCollection
{
    /**
     * Adds new field metadata to collection
     *
     * @param FieldMetadataInterface $metadata
     */
    public function add(FieldMetadataInterface $metadata)
    {
        $this->items[$metadata->getName()] = $metadata;
    }

    /**
     * Returns all metadata entries
     *
     * @return FieldMetadataInterface[]
     */
    public function all()
    {
        return $this->items;
    }

    /**
     * Returns the metadata for field or throw an exception if metadata was not found
     *
     * @param string $fieldName
     *
     * @return FieldMetadataInterface
     * @throws \InvalidArgumentException
     */
    public function get($fieldName)
    {
        if (!$this->has($fieldName)) {
            throw new \InvalidArgumentException(sprintf('Metadata for field "%s" does not exists', $fieldName));
        }

        return $this->items[$fieldName];
    }
}
