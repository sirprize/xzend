<?php

/**
 * Xzend - Sirprize's Zend Framework Extensions
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


require_once 'Zend/Application/Module/Bootstrap.php';


class Xzend_Application_Module_Bootstrap extends Zend_Application_Module_Bootstrap
{
    
	protected $_routesLoaded = false;
	protected $_optionsLoaded = false;
	protected $_configDir = null;
	protected $_routerConfigFile = 'router'; // filename withoud suffix
	protected $_moduleConfigFile = 'module'; // filename withoud suffix
	
	
	public function __construct($application)
    {
		parent::__construct($application);
		
		// set config dir for this module
		$this->_configDir = $this->getResourceLoader()->getBasePath().'/configs';
		
		// load module-specific routes
		$this->_loadRoutes();
		
		// register module configurator plugin which will call $this::onPreDispatch() on module access
		require_once 'Xzend/Controller/Plugin/ModuleConfigurator.php';
		$configurator = new Xzend_Controller_Plugin_ModuleConfigurator();
		$configurator->setModuleBootstrap($this);
		Zend_Controller_Front::getInstance()->registerPlugin($configurator);
	}
	
	
	
	public function onPreDispatch()
	{
		$module = Zend_Controller_Front::getInstance()->getRequest()->getModuleName();
		
		if(strtolower($module) != strtolower($this->getModuleName()))
		{
			// don't load if this is not the requested module
			return;
		}
		
		$this->_loadOptions();
		$this->bootstrap();
	}
	
	
	
	protected function _loadRoutes()
	{
		if($this->_routesLoaded)
		{
			return;
		}
		
		$this->_routesLoaded = true;
		$pathWithoutSuffix = $this->_configDir.'/'.$this->_routerConfigFile;
		
		$cfg = $this->_loadConfigFile($pathWithoutSuffix);
		
		if($cfg !== null && $cfg->resources->router->routes)
		{
			Zend_Controller_Front::getInstance()->getRouter()->addConfig($cfg->resources->router->routes);
		}
	}
	
	
	
	protected function _loadOptions()
	{
		if($this->_optionsLoaded)
		{
			return;
		}
		
		$this->_optionsLoaded = true;
		$pathWithoutSuffix = $this->_configDir.'/'.$this->_moduleConfigFile;
		$cfg = $this->_loadConfigFile($pathWithoutSuffix);
		
		if($cfg !== null)
		{
			$this->setOptions($cfg->toArray());
		}
	}
	
	
	
	protected function _loadConfigFile($pathWithoutSuffix)
    {
		$file = $pathWithoutSuffix.'.ini';
		
		if(is_file($file))
		{
			return new Zend_Config_Ini($file, $this->getEnvironment());
		}
		
		$file = $pathWithoutSuffix.'.xml';
		
		if(is_file($file))
		{
			return new Zend_Config_Xml($file, $this->getEnvironment());
		}
		
		$file = $pathWithoutSuffix.'.php';
		
		if(is_file($file))
		{
			$config = include $file;
			return new Zend_Config($config, true);
		}
		
		return null;
    }
	
}

