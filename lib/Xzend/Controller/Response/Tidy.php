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

require_once 'Zend/Controller/Response/Abstract.php';


class Xzend_Controller_Response_Tidy extends Zend_Controller_Response_Abstract
{
    
	
	protected $_config = array();
	protected $_echoTidyErrors = false;
	protected $_tidyEncoding = 'UTF8';
	protected $_enableTidy = true;
	
	
	
	public function enable()
	{
		$this->_enableTidy = true;
		return $this;
	}
	
	
	
	public function disable()
	{
		$this->_enableTidy = false;
		return $this;
	}
	
	

	public function setConfig(array $config)
	{
		$this->_config = $config;
		return $this;
	}
	
	
	public function echoTidyErrors($echoTidyErrors)
	{
		$this->_echoTidyErrors = $echoTidyErrors;
		return $this;
	}
	
	
	public function setTidyEncoding($tidyEncoding)
	{
		$this->_tidyEncoding = $tidyEncoding;
		return $this;
	}
	
	
	
    /**
     * Echo the body segments
     *
     * @return void
     */
    public function outputBody()
    {
        $body = implode('', $this->_body);
		
		if(!$this->_enableTidy)
		{
			echo $body;
		}
		else {
			$tidy = new tidy();
			$tidy->ParseString($body, $this->_config, $this->_tidyEncoding);
			#$tidy->cleanRepair();

			if($this->_echoTidyErrors && $tidy->errorBuffer)
			{
				echo $body;
				echo "\n\n\n<!--\n";
			    echo "tidy detected the following errors:\n";
			    echo $tidy->errorBuffer;
				echo "\n-->";
			}
			else {
				echo $tidy->root();
			}
		}
    }

}