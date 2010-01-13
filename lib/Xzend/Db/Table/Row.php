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


require_once 'Zend/Db/Table/Row/Abstract.php';


class Xzend_Db_Table_Row extends Zend_Db_Table_Row_Abstract
{
	
    public function __call($method, array $args)
    {
    	# check if $method exists in case this method is called directly
    	if(method_exists($this, $method))
    	{
    		return call_user_func(array($this, $method), $args);
    	}
    	
        if(preg_match('/^set(\w*)/', $method, $matches))
        {
        	$first = mb_convert_case(mb_substr($matches[1], 0, 1), MB_CASE_LOWER);
			$column = preg_replace('/^./', $first, $matches[1]);
        	
        	if(count($args))
        	{
        		$this->__set($column, $args[0]);
        		return $this;
        	}
        }
        else if(preg_match('/^get(\w*)/', $method, $matches))
        {
        	$first = mb_convert_case(mb_substr($matches[1], 0, 1), MB_CASE_LOWER);
			$column = preg_replace('/^./', $first, $matches[1]);
			return $this->__get($column);
        }
        
        parent::__call($method, $args);
    }
    
}