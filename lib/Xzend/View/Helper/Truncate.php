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


require_once 'Zend/View/Helper/Abstract.php';


class Xzend_View_Helper_Truncate extends Zend_View_Helper_Abstract
{

    public function truncate(
    	$string,
    	$length = 80,
    	$etc = '...',
    	$breakWords = false,
    	$middle = false
    )
	{
		if ($length == 0)
			return '';
	
		if (mb_strlen($string) > $length) {
			$length -= min($length, mb_strlen($etc));
			if (!$breakWords && !$middle) {
				$string = preg_replace('/\s+?(\S+)?$/', '', mb_substr($string, 0, $length+1));
			}
			if(!$middle) {
				return mb_substr($string, 0, $length) . $etc;
			} else {
				return mb_substr($string, 0, $length/2) . $etc . mb_substr($string, -$length/2);
			}
		} else {
			return $string;
		}
	}
}