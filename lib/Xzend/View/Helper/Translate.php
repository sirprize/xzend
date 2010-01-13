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


require_once 'Zend/View/Helper/Translate.php';


class Xzend_View_Helper_Translate extends Zend_View_Helper_Translate
{


	public function setIniFilenameScanTranslator($path, $defaultLanguage, $language = null)
    {
		require_once 'Zend/Translate.php';
		$translate = new Zend_Translate(
			Zend_Translate::AN_INI,
			$path,
			$defaultLanguage,
			array('scan' => Zend_Translate::LOCALE_FILENAME)
		);
		
		if($translate->isAvailable($language))
		{
			$translate->setLocale($language);
		}
		
		$this->setTranslator($translate->getAdapter());
    }

}