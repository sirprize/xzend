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


class Xzend_Module
{
	
	protected $_config = null;
	
	
	
	public function __construct(array $config)
    {
		$this->_config = $config;
    }
	
	
	protected function _getConfig($name)
	{
		if(!isset($this->_config[$name]))
		{
			require_once 'Xzend/Module/Exception.php';
            throw new Xzend_Module_Exception("missing '$name' config");
		}
		
        return $this->_config[$name];
	}
	
}