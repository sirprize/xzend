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


namespace Xzend\Session;


class Param
{
    
    protected $_request = null;
	protected $_namespace = null;
    protected $_param = null;
	protected $_val = null;
	protected $_started = false;
    

    public function __construct(\Zend_Controller_Request_Http $request, $param)
    {
		$this->_request = $request;
		$this->_param = (string) $param;
    }
    
	/*
    public function setParam($param)
    {
		if($param === null)
		{
			if($this->_param === null)
			{
				require_once 'Xzend/Session/Exception.php';
				throw new \Xzend\Session\Exception("param must be set with a valid string");
			}
			return $this;
		}
		
		if($this->_param !== null && $this->_param != $param)
		{
			require_once 'Xzend/Session/Exception.php';
			throw new \Xzend\Session\Exception("param has already been set to '$this->_param'");
		}
		
    	$this->_param = $param;
    	return $this;
    }
    */
	
    public function getParam()
    {
    	return $this->_param;
    }
    
    
    public function getVal($default = null)
    {
		if(!$this->_started)
		{
			$this->_val = $this->_establishValue($this->_param);
			$this->_started = true;
		}
		
        return ($this->_val !== null) ? $this->_val : $default;
    }
    
    
    public function setVal($val)
    {
        $this->_val = $val;
		$this->_getNamespace()->{$this->_param} = $val;
		$this->_started = true;
        return $this;
    }

	
	/*
	public function setVal($val, $param = null)
    {
		$this->setParam($param);
        $this->_val = $val;
		$this->_getNamespace()->{$this->_param} = $val;
		$this->_started = true;
        return $this;
    }
	*/
    
    
    protected function _getNamespace()
    {
		if($this->_namespace === null)
		{
			require_once 'Zend/Session/Namespace.php';
	    	$this->_namespace = new \Zend_Session_Namespace(__CLASS__);
		}
		
		return $this->_namespace;
    }
	
    
    protected function _establishValue($param)
    {
    	$val = (isset($this->_getNamespace()->$param)) ? $this->_getNamespace()->$param : null;
		$val = ($this->_request->get($param) !== null) ? $this->_request->get($param) : $val;
        if(!$val) { unset($this->_getNamespace()->$param); }
        else { $this->_getNamespace()->$param = $val; }
        #print $param.' = '.$val.'<br />';
        return $val;
    }
    
}