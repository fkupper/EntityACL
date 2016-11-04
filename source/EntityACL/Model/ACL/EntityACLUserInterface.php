<?php

namespace EntityACL\Model\ACL;

/**
 * EntityACLUserInterface
 * 
 * Interface for user entities implementing required EntityACL methods
 * @since 20/02/2013
 * @package EntityACL\Model\ACL
 * @author fkupper
 */
interface EntityACLUserInterface {

    /**
     * @param string $password
     */
    public function setPassword($password);
    
    /**
     * @param string $username
     */
    public function setUsername($username);
    
    /**
     * @return string
     */
    public function getPassword();
    
    /**
     * @return string
     */
    public function getUsername();
    
}
