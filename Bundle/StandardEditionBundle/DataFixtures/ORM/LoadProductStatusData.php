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

namespace WellCommerce\Bundle\StandardEditionBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use WellCommerce\Bundle\CatalogBundle\Entity\ProductStatus;
use WellCommerce\Bundle\StandardEditionBundle\DataFixtures\AbstractDataFixture;

/**
 * Class LoadProductStatusData
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class LoadProductStatusData extends AbstractDataFixture
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        if (!$this->isEnabled()) {
            return;
        }
        
        $bestseller = new ProductStatus();
        $bestseller->setSymbol('bestseller');
        foreach ($this->getLocales() as $locale) {
            $bestseller->translate($locale->getCode())->setName('Bestsellers');
            $bestseller->translate($locale->getCode())->setSlug($locale->getCode() . '/' . 'bestseller');
            $bestseller->translate($locale->getCode())->setCssClass('bestseller');
            $bestseller->translate($locale->getCode())->getMeta()->setTitle('Bestselling products');
        }
        
        $bestseller->mergeNewTranslations();
        $manager->persist($bestseller);
        $this->addReference('product_status_bestseller', $bestseller);
        
        $featured = new ProductStatus();
        $featured->setSymbol('featured');
        foreach ($this->getLocales() as $locale) {
            $featured->translate($locale->getCode())->setName('Featured');
            $featured->translate($locale->getCode())->setSlug($locale->getCode() . '/' . 'featured');
            $featured->translate($locale->getCode())->setCssClass('featured');
            $featured->translate($locale->getCode())->getMeta()->setTitle('Featured products');
        }
        $featured->mergeNewTranslations();
        $manager->persist($featured);
        $this->addReference('product_status_featured', $featured);
        
        $novelty = new ProductStatus();
        $novelty->setSymbol('novelty');
        foreach ($this->getLocales() as $locale) {
            $novelty->translate($locale->getCode())->setName('New products');
            $novelty->translate($locale->getCode())->setSlug($locale->getCode() . '/' . 'novelty');
            $novelty->translate($locale->getCode())->setCssClass('novelty');
            $novelty->translate($locale->getCode())->getMeta()->setTitle('New products');
        }
        
        $novelty->mergeNewTranslations();
        $manager->persist($novelty);
        $this->addReference('product_status_novelty', $novelty);
        
        $promotion = new ProductStatus();
        $promotion->setSymbol('promotion');
        foreach ($this->getLocales() as $locale) {
            $promotion->translate($locale->getCode())->setName('Promotions');
            $promotion->translate($locale->getCode())->setSlug($locale->getCode() . '/' . 'promotion');
            $promotion->translate($locale->getCode())->setCssClass('promotion');
            $promotion->translate($locale->getCode())->getMeta()->setTitle('Promotions & special offers');
        }
        
        $promotion->mergeNewTranslations();
        $manager->persist($promotion);
        $this->addReference('product_status_promotion', $promotion);
        
        $manager->flush();
        
        $this->createLayoutBoxes($manager, [
            'bestsellers'       => [
                'type'     => 'ProductStatus',
                'name'     => 'Bestsellers',
                'settings' => [
                    'status' => $this->getReference('product_status_bestseller')->getId(),
                ],
            ],
            'new_products'      => [
                'type'     => 'ProductStatus',
                'name'     => 'New arrivals',
                'settings' => [
                    'status' => $this->getReference('product_status_novelty')->getId(),
                ],
            ],
            'featured_products' => [
                'type'     => 'ProductStatus',
                'name'     => 'Featured products',
                'settings' => [
                    'status' => $this->getReference('product_status_featured')->getId(),
                ],
            ],
            'promotions'        => [
                'type'     => 'ProductStatus',
                'name'     => 'Promotions',
                'settings' => [
                    'status' => $this->getReference('product_status_promotion')->getId(),
                ],
            ],
            'dynamic_status'    => [
                'type' => 'ProductStatus',
                'name' => 'Dynamic product status box',
            ],
        ]);
        
        $manager->flush();
    }
}
