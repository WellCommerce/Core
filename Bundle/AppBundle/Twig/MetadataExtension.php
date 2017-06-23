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

namespace WellCommerce\Bundle\AppBundle\Twig;

use WellCommerce\Bundle\AppBundle\Entity\Meta;
use WellCommerce\Bundle\AppBundle\Service\Metadata\Helper\MetadataHelperInterface;

/**
 * Class MetadataExtension
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class MetadataExtension extends \Twig_Extension
{
    /**
     * @var MetadataHelperInterface
     */
    protected $metadataHelper;
    
    public function __construct(MetadataHelperInterface $metadataHelper)
    {
        $this->metadataHelper = $metadataHelper;
    }
    
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('metadata', [$this, 'getMetadata'], ['is_safe' => ['html']]),
        ];
    }
    
    public function getMetadata(): Meta
    {
        return $this->metadataHelper->getMetadata();
    }
    
    public function getName()
    {
        return 'metadata';
    }
}
