<?php
/**
 * Created by PhpStorm.
 * User: steve
 * Date: 11/10/17
 * Time: 16:24
 */

namespace Eukles\Entity\Middleware;

use Eukles\Container\ContainerInterface;
use Eukles\Container\ContainerTrait;
use Eukles\Service\Router\Route;
use Psr\Http\Message\ResponseInterface;

/**
 * Class EntityCreate
 *
 * @package Eukles\Entity\Middleware
 */
class EntityCreate
{
    
    use ContainerTrait;
    /**
     * @var Route
     */
    protected $route;
    
    /**
     * EntityCreate constructor.
     *
     * @param ContainerInterface $container
     * @param Route              $route
     */
    public function __construct(ContainerInterface $container, Route $route)
    {
        $this->container = $container;
        $this->route     = $route;
    }
    
    /**
     * @param $request
     * @param $response
     * @param $next
     *
     * @return ResponseInterface
     */
    public function __invoke($request, $response, $next): ResponseInterface
    {
        $requestClass = $this->route->getRequestClass();
        /** @var ContainerInterface $this */
        $response = $this->container->getEntityFactory()->create(
            new $requestClass($this->container),
            $request,
            $response,
            $next,
            $this->route->getNameOfInjectedParam(),
            $this->route->hasToUseRequest()
        );
        
        return $response;
    }
}