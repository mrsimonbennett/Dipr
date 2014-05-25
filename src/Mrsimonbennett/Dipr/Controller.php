<?php

namespace Mrsimonbennett\Dipr;

use Illuminate\Routing\Controller as LaravelController;
use ReflectionMethod;

/**
 * Class Controller
 * @package Mrsimonbennett\Dipr
 */
class Controller extends LaravelController
{
    /**
     * @param string $method
     * @param array  $routingParameters
     * @return \Illuminate\View\View|mixed
     */
    public function callAction($method, $routingParameters)
    {
        $objects = [];
        $this->setupLayout();

        $methodParams = $this->detectParameters($method);

        foreach ($routingParameters as $rpKey => $rpValue) {
            if (is_object($rpValue)) {
                $objects[get_class($rpValue)] = $rpValue;
                unset($routingParameters[$rpKey]);
            }
        }

        $parameters = array_merge($this->matchClasses($methodParams, $objects), $routingParameters);

        $response = call_user_func_array(array($this, $method), $parameters);

        // If no response is returned from the controller action and a layout is being
        // used we will assume we want to just return the layout view as any nested
        // views were probably bound on this view during this controller actions.
        if (is_null($response) && !is_null($this->layout)) {
            $response = $this->layout;
        }

        return $response;
    }

    /**
     * @param $method
     * @return \ReflectionParameter[]
     */
    private function detectParameters($method)
    {
        $reflection = new ReflectionMethod($this, $method);
        $methodParams = ($reflection->getParameters());
        return $methodParams;
    }

    /**
     * @param $methodParams
     * @param $objects
     * @return mixed
     */
    private function matchClasses($methodParams, $objects)
    {
        $parameters = [];
        for ($i = 0; $i < count($methodParams); $i++) {
            if (!is_object($methodParams[$i]->getClass()))
                break;
            $name = ($methodParams[$i]->getClass()->name);
            if (isset($objects[$name])) {
                $parameters[$i] = $objects[$name];
                unset($objects[$name]);
            } else {
                $parameters[$i] = \App::make($name); //This is not good
            }
        }
        return $parameters;
    }
} 