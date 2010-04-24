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


class Xzend_Project
{
	
	protected $_isDebug = false;
	protected $_useCustomDojoBuild = false;
	protected $_paths = array();
	
	
	public function __construct(array $options)
	{
		$this->_isDebug = (isset($options['isDebug'])) ? $options['isDebug'] : false;
		$this->_useCustomDojoBuild = (isset($options['useCustomDojoBuild'])) ? $options['useCustomDojoBuild'] : false;
		$this->_paths = (isset($options['paths'])) ? (array) $options['paths'] : array();
	}
	
	
	public function setIsDebug($isDebug)
	{
		$this->_isDebug = $isDebug;
		return $this;
	}
	
	
	public function getIsDebug()
	{
		return $this->_isDebug;
	}
	
	
	public function useCustomDojoBuild()
	{
		return $this->_useCustomDojoBuild;
	}
	
	
	public function getPath($name)
	{
		if(!isset($this->_paths[$name]))
		{
			require_once 'Xzend/Exception.php';
            throw new Xzend_Exception("path '$name' is not defined");
		}
		
        return $this->_paths[$name];
	}
    
}