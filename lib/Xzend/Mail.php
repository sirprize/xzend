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


require_once 'Zend/Mail.php';


class Xzend_Mail extends Zend_Mail
{
	
	protected $_view = null;
	protected $_template = null;
	protected $_encoding = null;
	protected $_isHtml = true;
	
	
	public function setTemplate($template)
	{
		$this->_template = $template;
		return $this;
	}
	
	
	public function getTemplate()
	{
		if($this->_template === null)
		{
			require_once 'Xzend/Mail/Exception.php';
    		throw new Xzend_Mail_Exception('call setTemplate() before '.__METHOD__);
		}
		
		return $this->_template;
	}
	
	
	public function getView()
	{
		if($this->_view === null)
		{
			require_once 'Zend/View.php';
			$this->_view = new Zend_View();
		}
		return $this->_view;
	}
	
	
	public function setIsHtml($bool)
	{
		$this->_isHtml = $bool;
		return $this;
	}
	
	
	public function isHtml()
	{
		return $this->_isHtml;
	}
	
	
	public function setEncoding($encoding)
	{
		$this->_encoding = $encoding;
		return $this;
	}
	
	
	public function getEncoding()
	{
		if($this->_encoding === null)
		{
			require_once 'Zend/Mime.php';
			return Zend_Mime::ENCODING_QUOTEDPRINTABLE;
		}
		return $this->_encoding;
	}
	
	
	public function render()
	{
		$this->getView()->setScriptPath(dirname($this->getTemplate()));
		$body = $this->getView()->render(basename($this->getTemplate()));
		
		if($this->isHtml())
		{
			$this->setBodyHtml($body, $this->getCharset(), $this->getEncoding());
		}
		else {
			$this->setBodyText($body, $this->getCharset(), $this->getEncoding());
		}
		
		return $this;
	}
}