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

namespace WellCommerce\Bundle\CoreBundle\Helper\Templating;

use ReflectionClass;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Twig_Environment as Environment;
use WellCommerce\Bundle\CoreBundle\Controller\ControllerInterface;

/**
 * Class TemplatingHelper
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class TemplatingHelper implements TemplatingHelperInterface
{
    /**
     * @var EngineInterface
     */
    protected $engine;
    
    /**
     * @var Environment
     */
    protected $environment;
    
    /**
     * @var KernelInterface
     */
    protected $kernel;
    
    public function __construct(EngineInterface $engine, KernelInterface $kernel, Environment $environment)
    {
        $this->engine      = $engine;
        $this->environment = $environment;
        $this->kernel      = $kernel;
    }
    
    public function render(string $name, array $parameters = []): string
    {
        return $this->engine->render($name, $parameters);
    }
    
    public function renderTemplateString(string $template, array $parameters = []): string
    {
        $template = $this->environment->createTemplate($template);
        
        return $template->render($parameters);
    }
    
    public function renderControllerResponse(ControllerInterface $controller, string $templateName, array $parameters = []): Response
    {
        $template = $this->resolveControllerTemplate($controller, $templateName);
        
        return $this->engine->renderResponse($template, $parameters);
    }
    
    public function resolveControllerTemplate(ControllerInterface $controller, string $templateName): string
    {
        $reflectionClass = new ReflectionClass($controller);
        $controllerName  = $this->getControllerLogicalName($reflectionClass);
        $bundleName      = $this->getBundleName($reflectionClass);
        
        return sprintf('%s:%s:%s.html.twig', $bundleName, $controllerName, $templateName);
    }
    
    protected function getControllerLogicalName(ReflectionClass $reflectionClass): string
    {
        $className = $reflectionClass->getName();
        preg_match('/Controller\\\(.+)Controller$/', $className, $matchController);
        
        return $matchController[1];
    }
    
    protected function getBundleName(ReflectionClass $reflectionClass): string
    {
        $currentBundle = $this->getBundleForClass($reflectionClass);
        
        return $currentBundle->getName();
    }
    
    protected function getBundleForClass(ReflectionClass $reflectionClass): BundleInterface
    {
        $bundles = $this->kernel->getBundles();
        
        do {
            $namespace = $reflectionClass->getNamespaceName();
            foreach ($bundles as $bundle) {
                if (0 === strpos($namespace, $bundle->getNamespace())) {
                    return $bundle;
                }
            }
            $reflectionClass = $reflectionClass->getParentClass();
        } while ($reflectionClass);
    }
}
