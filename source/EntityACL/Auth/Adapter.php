<?php

namespace EntityACL\Auth;

use EntityACL\Model\ACL\EntityACLUserInterface;
use EntityACL\Model\ACL\EntityACLUserRepositoryInterface;
/**
 * ZF AuthAdapter adapted for EntityACL
 * @package EntityACL\Auth
 * @since 04/02/2013
 * @author fkupper
 * @uses \EntityACL\Model\ACL\EntityACLUserInterface
 * @uses \EntityACL\Model\ACL\EntityACLUserRepositoryInterface
 * @uses \Zend_Auth_Adapter_Interface
 * @uses \Zend_Auth_Result
 */
class Adapter
    implements \Zend_Auth_Adapter_Interface
{
    
    /**
     * @todo finish approval feature 
     */
    const BAD_DATA_MESSAGE = "Username or password invalid.";
    const UNAPROVED_MESSAGE = "User is not approved, wait for approval.";
    const WRONG_DATA = 1;
    const UNAPROVED = 2;

    /**
     *
     * @var EntityACLUserInterface
     */
    protected $user;

    /**
     *
     * @var string
     */
    protected $username;

    /**
     *
     * @var string
     */
    protected $password;
    
    /**
     *
     * @var EntityACLUserRepositoryInterface
     */
    protected $repository;

    /**
     * Sets the credential of the adapter.
     * @param string $credential
     * @return \EntityACL\Auth\Adapter
     */
    public function setCredential($credential){
        $this->password = $credential;
        return $this;
    }

    /**
     * Sets the identity of the adapter.
     * @param string $identity
     * @return \EntityACL\Auth\Adapter
     */
    public function setIdentity($identity){
        $this->username = $identity;
        return $this;
    }

    /**
     * Instead of using setCredential and setIdentity, this method accepts 
     * an @see \EntityACL\Model\ACL\EntityACLUserInterface or a array with the 
     * elements 'username' and 'password' to fill authentication data
     * 
     * @param Array | EntityACLUserInterface $userEntity
     * @return \EntityACL\Auth\Adapter
     */
    public function setUserEntity($userEntity) {
        if($userEntity instanceof EntityACLUserInterface){
            $this->user = $userEntity;
        }
        elseif ($userEntity && is_array($userEntity)) {
            $this->user->setUsername($userEntity['username']);
            $this->user->setPassword($userEntity['password']);
        }

        return $this;
    }
    
    /**
     * Sets the repository to get user entity and check for username and password
     * @param EntityACLUserRepositoryInterface $repository
     * @throws Exception
     */
    public function setRepository(EntityACLUserRepositoryInterface $repository){
        if($repository instanceof EntityACLUserRepositoryInterface){
            $this->repository = $repository;
        }else{
            throw new Exception('$repository must implement EntityACLUserRepositoryInterface');
        }
        
    }

    /**
     * Returns the fetched user after authentication
     * @todo Check if still required
     * @return \EntityACL\Model\ACL\EntityACLUserInterface
     */
    public function getResultRowObject() {
        return $this->user;
    }


    /**
     * Attempt to authenticate using username and password provided
     *
     * @throws Zend_Auth_Adapter_Exception if authentication fails 
     * @return Zend_Auth_Result
     */
    public function authenticate()
    {
        try
        {
            $this->checkUser();
        }
        catch (\Exception $e)
        {
            switch ($e->getMessage()) {
                case self::WRONG_DATA:
                    return $this->result(\Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID, self::BAD_DATA_MESSAGE);
                case self::UNAPROVED:
                    return $this->result(\Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID, self::UNAPROVED_MESSAGE);
                default:
                    throw $e;
            }
        }
        return $this->result(\Zend_Auth_Result::SUCCESS);

    }

    /**
     * Looks for a user with the given username and password criteria inside the
     * provided repository
     */
    private function checkUser() {
       
        if($this->user instanceof EntityACLUserInterface &&
           !empty($this->user->getUsername()) &&
           !empty($this->user->getUsername())){
            $userEntity = $this->repository->findOneBy($this->user->getUsername(), $this->user->getPassword());            
        }else{
            $userEntity = $this->repository->findOneBy($this->username, $this->password);            
        }
        
        if (!$userEntity) {
            throw new \Exception(self::WRONG_DATA);
        }

        $this->setUserEntity($userEntity);
        
    }

    /**
     * Factory for Zend_Auth_Result
     *
     * @param integer    The result code
     * @param mixed | array | string      The message, may be string or array
     * @return Zend_Auth_Result
     */
    public function result($code, $messages = array()) {
        if (!is_array($messages)) {
            $messages = array($messages);
        }
         return new \Zend_Auth_Result(
            $code,
            $this->user,
            $messages
        );
    }
    
    /**
     * Checks if the adapter has credentials
     * @return boolean
     */
    
    public function hasCredentials(){
        if($this->user != null){
            if(($this->user instanceof EntityACLUserInterface &&
                !empty($this->user->getUsername()) &&
                !empty($this->user->getUsername())) ||
                (!empty($this->username) &&
                 !empty($this->password))){
                return true;
            }
        }
        return false;
    }
}

