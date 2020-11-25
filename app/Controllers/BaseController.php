<?php
namespace App\Controllers;
use Psr\Container\ContainerInterface;

class BaseController{
    protected $container;

    //Constructor
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
}