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


namespace Xzend\Doctrine\DBAL\Logging;


require_once 'Doctrine/DBAL/Logging/SQLLogger.php';


class SQLLogger implements \Doctrine\DBAL\Logging\SQLLogger
{
    /**
     * @var \Zend_Log
     */
    private $_zendLog;

    /**
     * Sets the \Zend_Log instance to use.
     *
     * @param \Zend_Log $zendLog
     */
    public function setZendLog(\Zend_Log $zendLog)
    {
        $this->_zendLog = $zendLog;
    }

    /**
     * Gets the \Zend_Log instance used by the cache.
     *
     * @return \Zend_Log
     */
    public function getZendLog()
    {
        return $this->_zendLog;
    }
	
	
    function logSQL($sql, array $params = null)
	{
		$p = '';
		
		foreach($params as $k => $v)
		{
			if(is_object($v))
			{
				continue;
			}
			$p .= (($p) ? ', ' : '').$k.' => '.$v;
		}
		
		$this->getZendLog()->debug($sql);
		$this->getZendLog()->debug("PARAMS: $p");
	}
}