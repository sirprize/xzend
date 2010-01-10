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
 
 
require_once 'Zend/Controller/Router/Route.php';


class Xzend_Controller_Router_Route_Rest extends Zend_Controller_Router_Route
{
	
	protected $_actionKey = 'action';
	protected $_moduleKey = 'module';
	protected $_restMethodKey = '_method';
	protected $_representationKey = 'representation';
	
	
	
	
	public static function getInstance(Zend_Config $config)
    {
		$keys = ($config->keys instanceof Zend_Config) ? $config->keys->toArray() : array();
        $reqs = ($config->reqs instanceof Zend_Config) ? $config->reqs->toArray() : array();
        $defs = ($config->defaults instanceof Zend_Config) ? $config->defaults->toArray() : array();
        $route = new self($config->route, $defs, $reqs);
		$route
			->setActionKey((isset($keys['action'])) ? $keys['action'] : $route->getActionKey())
			->setModuleKey((isset($keys['module'])) ? $keys['module'] : $route->getModuleKey())
			->setRestMethodKey((isset($keys['method'])) ? $keys['method'] : $route->getRestMethodKey())
			->setRepresentationKey((isset($keys['representation'])) ? $keys['representation'] : $route->getRepresentationKey())
		;
		return $route;
    }
	
    
    
    public function match($path)
    {
		if($this->_actionKey === null)
    	{
			require_once 'Xzend/Controller/Exception.php';
			throw new Xzend_Controller_Exception('call setActionKey() before '.__METHOD__);
		}
		
		if($this->_restMethodKey === null)
    	{
			require_once 'Xzend/Controller/Exception.php';
			throw new Xzend_Controller_Exception('call setRestMethod() before '.__METHOD__);
		}
		
    	$representation = null;
    	
    	if(preg_match('/(.*)\.(\w*)$/', $path, $matches))
    	{
    		$path = $matches[1];
    		$representation = $matches[2];
    	}
    	
    	$return = parent::match($path);
    	
    	if(!$return)
    	{
    		return false;
    	}
    	
    	if($this->_representationKey !== null)
    	{
    		if($representation !== null)
    		{
    			$return[$this->_representationKey] = $representation;
    		}
    		else if(isset($this->_defaults[$this->_representationKey]))
    		{
    			$return[$this->_representationKey] = $this->_defaults[$this->_representationKey];
    		}
    	}
		
		$defaults = $this->getDefaults();
		
		if(!isset($defaults[$this->_getRestMethod()]))
		{
			return false;
		}
		
		$return[$this->_actionKey] = $defaults[$this->_getRestMethod()];
		
		unset($return['get']);
		unset($return['put']);
		unset($return['post']);
		unset($return['head']);
		unset($return['delete']);
		return $return;
    }
    
    
    
    public function assemble($data = array(), $reset = false, $encode = false)
    {
		$return = parent::assemble($data, $reset, $encode);
		
		if($this->_representationKey !== null)
    	{
    		if($return && isset($data[$this->_representationKey]))
			{
				$return .= '.'.$data[$this->_representationKey];
			}
    	}
		return $return;
    }
    
    
	public function setRestMethodKey($restMethodKey)
	{
		$this->_restMethodKey = $restMethodKey;
		return $this;
	}
	
	
	public function getRestMethodKey()
	{
		return $this->_restMethodKey;
	}
	
	
	public function setActionKey($actionKey)
	{
		$this->_actionKey = $actionKey;
		return $this;
	}
	
	
	public function getActionKey()
	{
		return $this->_actionKey;
	}
	
	
	public function setModuleKey($moduleKey)
	{
		$this->_moduleKey = $moduleKey;
		return $this;
	}
	
	
	public function getModuleKey()
	{
		return $this->_moduleKey;
	}
	
	
	public function setRepresentationKey($representationKey)
	{
		$this->_representationKey = $representationKey;
		return $this;
	}
	
	
	public function getRepresentationKey()
	{
		return $this->_representationKey;
	}
	
	
	protected function _getRestMethod()
    {
		// todo: handle calls with http X-Method-Override
		
    	$method = (isset($_SERVER['REQUEST_METHOD'])) ? strtolower($_SERVER['REQUEST_METHOD']) : '';
    	
    	if($method == 'post')
    	{
    		if(isset($_POST[$this->_restMethodKey]))
    		{
    			switch(trim(strtolower($_POST[$this->_restMethodKey])))
    			{
    				case 'put': return 'put';
    				case 'delete': return 'delete';
    				case 'head': return 'head';
    			}
			}
			return 'post';
		}
		
		switch(trim(strtolower($method)))
		{
			case 'put': return 'put';
			case 'delete': return 'delete';
			case 'head': return 'head';
		}
		
		return 'get';
    }
}