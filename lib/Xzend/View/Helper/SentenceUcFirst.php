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
    
    public function sentenceUcFirst($val)
    {
    	require_once 'Sirprize/String.php';
        return \Sirprize\String::sentenceUcFirst($val);
    }
}