<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WellCommerce\Bundle\CoreBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use WellCommerce\Bundle\CoreBundle\Doctrine\Repository\RepositoryInterface;
use WellCommerce\Bundle\CoreBundle\Entity\RoutableSubjectInterface;
use WellCommerce\Bundle\CoreBundle\Entity\Route;
use WellCommerce\Bundle\CoreBundle\Helper\Router\RouterHelperInterface;

/**
 * Class UniqueRouteValidator
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class UniqueRouteValidator extends ConstraintValidator
{
    /**
     * @var RepositoryInterface
     */
    private $repository;
    
    /**
     * @var RouterHelperInterface
     */
    private $routerHelper;
    
    public function __construct(RepositoryInterface $repository, RouterHelperInterface $routerHelper)
    {
        $this->repository   = $repository;
        $this->routerHelper = $routerHelper;
    }
    
    /**
     * Validate the route entity
     *
     * @param mixed      $entity
     * @param Constraint $constraint
     */
    public function validate($entity, Constraint $constraint)
    {
        if (!$entity instanceof RoutableSubjectInterface) {
            throw new \InvalidArgumentException(sprintf('Expected instance of %s', RoutableSubjectInterface::class));
        }
        
        $route  = $entity->getRoute();
        $slug   = $entity->getSlug();
        $locale = $entity->getLocale();
        $result = $this->repository->findOneBy(['path' => $slug, 'locale' => $locale]);
        
        // route is unique always if no result was found
        if (null === $result) {
            return;
        }
        
        // skip validation if there is exact match
        if ($route instanceof Route && $result->getIdentifier()->getId() === $route->getIdentifier()->getId()) {
            return;
        }
        
        if ($this->context instanceof ExecutionContextInterface) {
            $this->context
                ->buildViolation($constraint->message)
                ->setParameter('{{ type }}', $this->formatValue($result->getType()))
                ->setParameter('{{ url }}', $this->generatePath($result))
                ->atPath('slug')
                ->setInvalidValue($slug)
                ->addViolation();
        }
    }
    
    private function generatePath(Route $route): string
    {
        return $this->routerHelper->generateUrl('dynamic_' . $route->getId());
    }
}
