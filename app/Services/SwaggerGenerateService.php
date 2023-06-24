<?php

namespace App\Services;

use Illuminate\Routing\RouteCollection;
use Illuminate\Support\Facades\Route as RouteFacade;
use \Illuminate\Routing\Route;

class SwaggerGenerateService
{
    private $apiRoutes = [];

    public function __construct()
    {
        $this->generateDocs();
    }
    public function generateDocs(): void
    {
        $allRoutes = RouteFacade::getRoutes()->getRoutes();
        $this->apiRoutes = $this->getApiRoutes($allRoutes);
        dd($this->apiRoutes);



    }



    private function getApiRoutes(array $allRoutes): array
    {
        $apiRoutes = [];
        foreach ($allRoutes as $route) {
            if (!array_key_exists('controller', $route->action)) {
                continue;
            }
            $action = $route->action['controller'];

            $controller = $this->getControllerNameFromAction($action);

            if (is_subclass_of($controller, 'App\Http\Controllers\ApiController')) {
                $requestClassName = $this->getRequestClassName($action);
                $route->formRequestValidation = new $requestClassName();
                $apiRoutes[] = $route;
            }
        }

        return $apiRoutes;
    }

    private function getControllerNameFromAction(string $action): string
    {
        return explode('@', $action)[0] ?? '';
    }

    private function getRequestClassName(string $action)
    {
        list($controllerClassName, $methodName) = explode('@', $action);

        $methodParams = (new \ReflectionMethod($controllerClassName, $methodName))->getParameters();
        $requestClassName = '';
        foreach ($methodParams as $param) {
            $paramTypeName = $param->getType()->getName();
            if (is_subclass_of($paramTypeName, 'Illuminate\Foundation\Http\FormRequest')) {
                $requestClassName = $paramTypeName;
            }
        }
        return $requestClassName;
    }

    private function getRequestRules(string $requestClassName): array
    {
        return (new $requestClassName())->rules();
    }


}