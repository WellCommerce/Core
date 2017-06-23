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

namespace WellCommerce\Bundle\ApiBundle\Handler;

use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use WellCommerce\Bundle\ApiBundle\Exception\ResourceNotFoundException;
use WellCommerce\Bundle\CoreBundle\Entity\EntityInterface;
use WellCommerce\Bundle\CoreBundle\Manager\ManagerInterface;

/**
 * Class RequestHandler
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class RequestHandler implements RequestHandlerInterface
{
    /**
     * @var ManagerInterface
     */
    protected $manager;
    
    /**
     * @var Serializer
     */
    protected $serializer;
    
    /**
     * @var string
     */
    protected $resourceType;
    
    /**
     * RequestHandler constructor.
     *
     * @param string           $resourceType
     * @param ManagerInterface $manager
     * @param Serializer       $serializer
     */
    public function __construct(string $resourceType, ManagerInterface $manager, Serializer $serializer)
    {
        $this->resourceType = $resourceType;
        $this->manager      = $manager;
        $this->serializer   = $serializer;
    }
    
    public function getResourceType(): string
    {
        return $this->resourceType;
    }
    
    public function getManager(): ManagerInterface
    {
        return $this->manager;
    }
    
    public function getSerializer(): Serializer
    {
        return $this->serializer;
    }
    
    public function handleListRequest(Request $request): Response
    {
        $limit    = $request->get('limit') ?? 10;
        $offset   = $request->get('offset') ?? 0;
        $orderBy  = $request->get('order_by') ?? 'id';
        $orderDir = $request->get('order_dir') ?? 'asc';
        $criteria = $request->request->get('where') ?? [];
        $result   = $this->manager->getRepository()->findBy($criteria, [$orderBy => $orderDir], $limit, $offset);
        $data     = $this->serializer->serialize($result, self::RESPONSE_FORMAT, ['group' => $this->getResourceType()]);
        
        return new Response($data);
    }
    
    public function handleCreateRequest(Request $request): Response
    {
        $result = $this->manager->initResource();
        $data   = $this->serializer->serialize($result, self::RESPONSE_FORMAT, ['group' => $this->getResourceType()]);
        
        return new Response($data);
    }
    
    public function handleDeleteRequest(Request $request, int $identifier): Response
    {
        $resource = $this->getResourceById($identifier);
        
        $this->manager->removeResource($resource);
        
        return new JsonResponse([
            'success'       => true,
            'identifier'    => $identifier,
            'resource_type' => $this->getResourceType(),
        ]);
    }
    
    public function handleUpdateRequest(Request $request, int $identifier): Response
    {
        $parameters = $request->request->all();
        $resource   = $this->getResourceById($identifier);
        $className  = ClassUtils::getRealClass(get_class($resource));
        $resource   = $this->serializer->denormalize($parameters, $className, self::RESPONSE_FORMAT, [
            'resource' => $resource,
        ]);
        
        $data = $this->serializer->serialize($resource, self::RESPONSE_FORMAT, ['group' => $this->getResourceType()]);
        
        $this->manager->updateResource($resource);
        
        return new Response($data);
    }
    
    public function handleGetRequest(Request $request, int $identifier): Response
    {
        $resource = $this->getResourceById($identifier);
        $data     = $this->serializer->serialize($resource, self::RESPONSE_FORMAT, ['group' => $this->getResourceType()]);
        
        return new Response($data);
    }
    
    public function handleCountRequest(): Response
    {
        $repository   = $this->manager->getRepository();
        $queryBuilder = $repository->getQueryBuilder();
        $query        = $queryBuilder->getQuery();
        $query->useQueryCache(true);
        $query->useResultCache(true);
        $paginator = new Paginator($query, true);
        $paginator->setUseOutputWalkers(false);
        
        $data = $this->serializer->serialize($paginator->count(), self::RESPONSE_FORMAT, ['group' => $this->getResourceType()]);
        
        return new Response($data);
    }
    
    private function getResourceById(int $identifier): EntityInterface
    {
        $resource = $this->manager->getRepository()->find($identifier);
        if (null === $resource) {
            throw new ResourceNotFoundException($this->getResourceType(), $identifier);
        }
        
        return $resource;
    }
}
