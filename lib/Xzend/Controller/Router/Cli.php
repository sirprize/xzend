<?php

/**
 * Xzend - Zend Framework Extensions
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 *
 * @package    Xzend
 * @copyright  Copyright (c) 2009, Christian Hoegl, Switzerland (http://sitengine.org)
 * @license    http://sitengine.org/license/new-bsd     New BSD License
 */


/*
 *
 * Inspired by Maugrim the Reaper
 * http://blog.astrumfutura.com/archives/2009/09.html
 *
 */


require_once 'Zend/Controller/Router/Interface.php';


class Xzend_Controller_Router_Cli implements Zend_Controller_Router_Interface
{
    public function route(Zend_Controller_Request_Abstract $dispatcher){}
    public function assemble($userParams, $name = null, $reset = false, $encode = true){}
    public function getFrontController(){}
    public function setFrontController(Zend_Controller_Front $controller){}
    public function setParam($name, $value){}
    public function setParams(array $params){}
    public function getParam($name){}
    public function getParams(){}
    public function clearParams($name = null){}
}