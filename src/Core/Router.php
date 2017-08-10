<?php
namespace easyPM\Core;

use easyPM\Controllers\ErrorController;
use easyPM\Controllers\RootController;
use easyPM\Controllers\UserController;
use easyPM\Core\EasyPmApp;

class Router
{
    private $app;
    private $routeMap;
    private static $regexPatters = [
        'number' => '\d+',
        'string' => '\w'
    ];

    public function __construct(EasyPmApp $app)
    {
        $this->app = $app;
        $json = file_get_contents(
            __DIR__ . '/../../config/routes.json'
        );
        $this->routeMap = json_decode($json, true);
    }

    public function route(Request $request) : string
    {
        $path = $request->getPath();
        if ($path === '/') {
            $rootController = new rootController($this->app, $request);
            return $rootController->root();
        }

        foreach ($this->routeMap as $route => $info) {
            $regexRoute = $this->getRegexRoute($route, $info);
            // 用routes中定义的route匹配PATH，并执行route中定义的操作：
            // 创建对于的Controller，调用routes中定义的method处理
            if (preg_match("@^/$regexRoute$@", $path)) {
                return $this->executeController($route, $path, $info, $request);
            }
        }
        $errorController = new ErrorController($this->app, $request);
        return $errorController->notFound();
    }

    // 把URI中的数值和字符串分别置换为\d+和\w,以便归一化Controller/View，例如：
    // equipment/:id 变成了 equipment/\d+。
    private function getRegexRoute(string $route, array $info) : string
    {
        if (isset($info['params'])) {
            foreach ($info['params'] as $name => $type) {
                $route = str_replace(':' . $name, self::$regexPatters[$type], $route);
            }
        }
        return $route;
    }

    private function extractParams(string $route, string $path) : array
    {
        $params = [];
        $pathParts = explode('/', $path);
        $routeParts = explode('/', $route);
        foreach ($routeParts as $key => $routePart) {
            if (strpos($routePart, ':') === 0) {
                $name = substr($routePart, 1);
                $params[$name] = $pathParts[$key+1];
            }
        }
        return $params;
    }

    private function executeController(
        string $route,
        string $path,
        array $info,
        Request $request
    ): string {
        $controllerName = '\easyPM\Controllers\\'
            . $info['controller'] . 'Controller';
        // 采用标准化命名方式，才可以运用简单的方式处理，复杂的方式应该采用其他依赖关系处理
        $controller = new $controllerName($this->app, $request);
        if (isset($info['login']) && $info['login']) {
            if ($request->getCookies()->has('user')) {
                $userId = $request->getCookies()->get('user');
                $controller->setUserId($userId);
            } else {
                $errorController = new UserController($this->app, $request);
                return $errorController->login();
            }
        }
        $params = $this->extractParams($route, $path);
        return call_user_func_array(
            [$controller, $info['method']],
            $params
        );
    }
}
