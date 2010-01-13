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


/*
 *
 * Inspired by Maugrim the Reaper
 * http://blog.astrumfutura.com/archives/2009/09.html
 *
 */


require_once 'Zend/Controller/Request/Abstract.php';


class Xzend_Controller_Request_Cli extends Zend_Controller_Request_Abstract
{

    protected $_getopt = null;
	
	
	
	protected function _getGetoptRules()
	{
		return array(
	        $this->getModuleKey().'|m-w' => 'Module (optional)',
	        $this->getControllerKey().'|c=w' => 'Controller (required)',
	        $this->getActionKey().'|a=w' => 'Action (required)'
	    );
	}
	
	
	
	
	public function getGetopt()
	{
		if($this->_getopt === null)
		{
			require_once 'Zend/Console/Getopt.php';
	        $this->_getopt = new Zend_Console_Getopt($this->_getGetoptRules());
		}
		
		return $this->_getopt;
	}
	
	
	

    public function __construct()
    {
		$flags = array(
			'--'.$this->getActionKey(), '-a',
			'--'.$this->getControllerKey(), '-c',
			'--'.$this->getModuleKey(), '-m'
		);
		
		$argv = array($_SERVER['argv'][0]);
		
	    foreach ($_SERVER['argv'] as $key => $value)
		{
	        if (in_array($value, $flags))
			{
	            $argv[] = $value;
	            $argv[] = $_SERVER['argv'][$key+1];
	        }
	    }
	
		require_once 'Zend/Console/Getopt.php';
        $getopt = new Zend_Console_Getopt($this->_getGetoptRules(), $argv);
		$getopt->parse();
		
        $this->setModuleName($getopt->getOption($this->getModuleKey()));
        $this->setControllerName($getopt->getOption($this->getControllerKey()));
        $this->setActionName($getopt->getOption($this->getActionKey()));
    }

}