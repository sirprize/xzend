<?php

/**
 * Xzend - An example serving as a pattern
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 *
 * @package    Xzend
 * @copyright  Copyright (c) 2007, Christian Hoegl, Switzerland (http://sitengine.org)
 * @license    http://sitengine.org/license/new-bsd     New BSD License
 */


require_once 'Zend/Session/SaveHandler/Interface.php';


class Xzend_Session_SaveHandler_Doctrine implements Zend_Session_SaveHandler_Interface
{
	
	
	const LIFETIME = 'lifetime';
    const OVERRIDE_LIFETIME = 'overrideLifetime';



	protected $_entityManager = null;
	
	/**
     * Session lifetime
     *
     * @var int
     */
    protected $_lifetime = false;

    /**
     * Whether or not the lifetime of an existing session should be overridden
     *
     * @var boolean
     */
    protected $_overrideLifetime = false;



	
	public function __construct($config)
    {
		if($config instanceof Zend_Config)
		{
            $config = $config->toArray();
        }
		else if (!is_array($config))
		{
            require_once 'Xzend/Session/SaveHandler/Exception.php';
            throw new Xzend_Session_SaveHandler_Exception(
                '$config must be an instance of Zend_Config or array of key/value pairs containing '
              . 'configuration options for Xzend_Session_SaveHandler_Doctrine.');
        }

        foreach ($config as $key => $value)
		{
            switch ($key) {
                case self::LIFETIME:
                    $this->setLifetime($value);
                    break;
                case self::OVERRIDE_LIFETIME:
                    $this->setOverrideLifetime($value);
                    break;
            }
        }
	}
	
	
	
	public function setEntityManager(\Doctrine\ORM\EntityManager $entityManager)
	{
		$this->_entityManager = $entityManager;
		return $this;
	}
	
	
	public function getEntityManager()
	{
		if($this->_entityManager === null)
		{
			require_once 'Xzend/Session/SaveHandler/Exception.php';
			throw new Xzend_Session_SaveHandler_Exception('call setEntityManager() before '.__METHOD__);
		}
		
		return $this->_entityManager;
	}
	
	/**
     * Set session lifetime and optional whether or not the lifetime of an existing session should be overridden
     *
     * $lifetime === false resets lifetime to session.gc_maxlifetime
     *
     * @param int $lifetime
     * @param boolean $overrideLifetime (optional)
     * @return Zend_Session_SaveHandler_DbTable
     */
    public function setLifetime($lifetime, $overrideLifetime = null)
    {
        if ($lifetime < 0) {
            /**
             * @see Xzend_Session_SaveHandler_Exception
             */
            require_once 'Xzend/Session/SaveHandler/Exception.php';
            throw new Xzend_Session_SaveHandler_Exception();
        } else if (empty($lifetime)) {
            $this->_lifetime = (int) ini_get('session.gc_maxlifetime');
        } else {
            $this->_lifetime = (int) $lifetime;
        }

        if ($overrideLifetime != null) {
            $this->setOverrideLifetime($overrideLifetime);
        }

        return $this;
    }

    /**
     * Retrieve session lifetime
     *
     * @return int
     */
    public function getLifetime()
    {
        return $this->_lifetime;
    }

    /**
     * Set whether or not the lifetime of an existing session should be overridden
     *
     * @param boolean $overrideLifetime
     * @return Zend_Session_SaveHandler_DbTable
     */
    public function setOverrideLifetime($overrideLifetime)
    {
        $this->_overrideLifetime = (boolean) $overrideLifetime;

        return $this;
    }

    /**
     * Retrieve whether or not the lifetime of an existing session should be overridden
     *
     * @return boolean
     */
    public function getOverrideLifetime()
    {
        return $this->_overrideLifetime;
    }
	
	
	
	public function open($save_path, $name) {}

    
    public function close() {}
    
    
	
	function read($id)
	{
		#$this->gc(1);
		$session = $this->_loadSession($id);
		return ($session === null) ? '' : $session->getData();
	}
	
	
	function write($id, $data)
	{
		$session = $this->_loadSession($id);
		
		if($session === null)
		{
			$session = new \Entities\Session();
			$session->setId($id);
		}
		
		$session->setData($data);
		$session->setMdate(time());
		$session->setLifetime($this->getLifetime());
		$this->getEntityManager()->persist($session);
		$this->getEntityManager()->flush();
	}
	
	
	function destroy($id)
	{
		$queryBuilder = $this->getEntityManager()->createQueryBuilder();

		$queryBuilder
			->delete('Entities\Session', 's')
		   	->where('s.id = :id')
		;
		
		$queryBuilder->getQuery()->setParameter('id', $id);
		$queryBuilder->getQuery()->execute();
		$this->getEntityManager()->flush();
	}
	
	
	function gc($maxlifetime)
	{
		# TODO
		# DELETE Entities\Session s WHERE s.mdate + s.lifetime < ?1
		# [Syntax Error] line 0, col 32: Error: Unexpected 's'
		
		/*
		$queryBuilder = $this->getEntityManager()->createQueryBuilder();
		$sum = $queryBuilder->expr()->sum('s.mdate', 's.lifetime');
		
		$queryBuilder
			->delete('Entities\Session', 's')
			->where($queryBuilder->expr()->lt($sum, '?1'))
		;
		#print $queryBuilder->getDql(); exit;
		$queryBuilder->getQuery()->setParameter(1, time());
		$queryBuilder->getQuery()->execute();
		$this->getEntityManager()->flush();
		*/
	}
	
	
	
	
	function _loadSession($id)
	{
		$queryBuilder = $this->getEntityManager()->createQueryBuilder();

		$queryBuilder
			->select('s')
			->from('Entities\Session', 's')
			->where('s.id = :id')
		;
		
		$queryBuilder->getQuery()->setParameter('id', $id);
		return $queryBuilder->getQuery()->getSingleResult();
	}
	
}