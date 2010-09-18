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


class Xzend_View_Helper_SentenceUcFirst extends Zend_View_Helper_Abstract
{
    
    public function sentenceUcFirst($s)
    {
    	$sentencesNew = array();
    	$sentences = explode('. ', $s);
    	
    	foreach($sentences as $sentence)
    	{
    		$sentence = trim($sentence);
    		$sentence = mb_convert_case($sentence, MB_CASE_LOWER);
    		# find first word and make it title cased
    		$sentence = preg_replace_callback(
				'/^([^\s]*)/i',
				create_function(
					'$matches',
					'return mb_convert_case($matches[0], MB_CASE_TITLE);'
				),
				$sentence
			);
			$sentencesNew[] = $sentence;
    	}
    	
    	return trim(implode('. ', $sentencesNew));
    }
}