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

require_once 'Zend/Controller/Plugin/Abstract.php';


class Xzend_Controller_Plugin_ModuleConfigurator extends Zend_Controller_Plugin_Abstract
{
	
	protected $_moduleBootstrap = null;
	
	
	public function setModuleBootstrap(Xzend_Application_Module_Bootstrap $moduleBootstrap)
	{
		$this->_moduleBootstrap = $moduleBootstrap;
		return $this;
	}
	
	
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
		if($this->_moduleBootstrap === null)
		{
			require_once 'Xzend/Controller/Exception.php';
			throw new Xzend_Controller_Exception('call setModuleBootstrap() before '.__METHOD__.' is called');
		}
		
		$this->_moduleBootstrap->onPreDispatch();
    }
}