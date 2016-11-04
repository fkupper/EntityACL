<?php

namespace EntityACL\Model\ACL;

/**
 * EntityACLUserRepositoryInterface
 * 
 * Interface getting user data from database
 * @since 20/02/2013
 * @package EntityACL\Model\ACL
 * @author fkupper
 */
interface EntityACLUserRepositoryInterface {

    /**
     * @param string $username
     * @param string $password
     * @return EntityACLUserRepositoryInterface
     */
    function findOneBy($username, $password);
    
}
