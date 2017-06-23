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

use ComposerRevisions\Revisions;
use Packagist\Api\Result\Package as RemotePackage;
use WellCommerce\Bundle\AppBundle\Entity\Package;
use WellCommerce\Bundle\CoreBundle\Helper\Package\PackageHelperInterface;
use WellCommerce\Bundle\CoreBundle\Manager\AbstractManager;

/**
 * Class PackageManager
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class PackageManager extends AbstractManager
{
    public function syncPackages(string $type)
    {
        $searchResults = $this->getHelper()->getPackages(['type' => $type]);
        
        foreach ($searchResults as $result) {
            $package = $this->getHelper()->getPackage($result);
            $this->syncPackage($package);
        }
        
        $this->getEntityManager()->flush();
    }
    
    protected function syncPackage(RemotePackage $remotePackage)
    {
        $repository   = $this->getRepository();
        $localPackage = $repository->findOneBy(['fullName' => $remotePackage->getName()]);
        if (!$localPackage instanceof Package) {
            $this->addPackage($remotePackage);
        } else {
            $this->setPackageVersions($localPackage);
            $this->getDoctrineHelper()->getEntityManager()->flush();
        }
    }
    
    protected function addPackage(RemotePackage $remotePackage)
    {
        list($vendor, $name) = explode('/', $remotePackage->getName());
        $package = new Package();
        $package->setFullName($remotePackage->getName());
        $package->setName($name);
        $package->setVendor($vendor);
        $this->setPackageVersions($package);
        $this->getDoctrineHelper()->getEntityManager()->persist($package);
    }
    
    public function changePackageStatus(Package $package)
    {
        $this->setPackageVersions($package);
        $this->updateResource($package);
    }
    
    protected function setPackageVersions(Package $package)
    {
        $branch        = PackageHelperInterface::DEFAULT_BRANCH_VERSION;
        $remotePackage = $this->getHelper()->getPackage($package->getFullName());
        $remoteVersion = $this->getPackageVersionReference($remotePackage->getVersions()[$branch]);
        $localVersion  = '';
        
        if (isset(Revisions::$byName[$package->getFullName()])) {
            $localVersion = Revisions::$byName[$package->getFullName()];
        }
        
        $package->setLocalVersion($localVersion);
        $package->setRemoteVersion($remoteVersion);
    }
    
    protected function getPackageVersionReference(RemotePackage\Version $version): string
    {
        return $version->getSource()->getReference();
    }
    
    private function getHelper(): PackageHelperInterface
    {
        return $this->get('package.helper');
    }
}
