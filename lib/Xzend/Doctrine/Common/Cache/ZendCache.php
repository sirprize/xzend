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


namespace Xzend\Doctrine\Common\Cache;


require_once 'Doctrine/Common/Cache/AbstractCache.php';


class ZendCache extends \Doctrine\Common\Cache\AbstractCache
{
    /**
     * @var \Zend_Cache_Core
     */
    private $_zendCache;

    /**
     * Sets the \Zend_Cache_Core instance to use.
     *
     * @param \Zend_Cache_Core $zendCache
     */
    public function setZendCache(\Zend_Cache_Core $zendCache)
    {
        $this->_zendCache = $zendCache;
    }

    /**
     * Gets the \Zend_Cache_Core instance used by the cache.
     *
     * @return \Zend_Cache_Core
     */
    public function getZendCache()
    {
        return $this->_zendCache;
    }

    /**
     * {@inheritdoc}
     */
    protected function _doFetch($id) 
    {
        return $this->_zendCache->load($this->_prepareId($id));
    }

    /**
     * {@inheritdoc}
     */
    protected function _doContains($id)
    {
        return (bool) $this->_zendCache->test($this->_prepareId($id));
    }

    /**
     * {@inheritdoc}
     */
    protected function _doSave($id, $data, $lifeTime = false)
    {
		return $this->_zendCache->save($data, $this->_prepareId($id), array(), $lifeTime);
    }

    /**
     * {@inheritdoc}
     */
    protected function _doDelete($id) 
    {
        return $this->_zendCache->remove($this->_prepareId($id));
    }
	
	
	protected function _prepareId($id)
	{
		return preg_replace('/[^a-zA-Z0-9_]/', '_', $id);
	}
	
	
	public function getIds()
	{
		return $this->_zendCache->getIds();
	}
}