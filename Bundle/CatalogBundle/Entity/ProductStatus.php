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

namespace WellCommerce\Bundle\CatalogBundle\Entity;

use Knp\DoctrineBehaviors\Model\Blameable\Blameable;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use Knp\DoctrineBehaviors\Model\Translatable\Translatable;
use WellCommerce\Bundle\CoreBundle\Doctrine\Behaviours\Identifiable;
use WellCommerce\Bundle\CoreBundle\Entity\EntityInterface;

/**
 * Class ProductStatus
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ProductStatus implements EntityInterface
{
    use Identifiable;
    use Translatable;
    use Timestampable;
    use Blameable;
    
    protected $symbol = '';
    
    public function getSymbol(): string
    {
        return $this->symbol;
    }
    
    public function setSymbol(string $symbol)
    {
        $this->symbol = $symbol;
    }
    
    public function translate($locale = null, $fallbackToDefault = true): ProductStatusTranslation
    {
        return $this->doTranslate($locale, $fallbackToDefault);
    }
}
