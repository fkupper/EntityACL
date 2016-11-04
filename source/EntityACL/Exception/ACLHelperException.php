<?php
namespace EntityACL\Exception;
/**
 * ACLHelperException
 * 
 * Exception used in @see \EntityACL\Controller\Helper\Acl.php validations
 * 
 * @since 15/05/2013
 * @package EntityACL\Exception
 * @author fkupper
 * @see \EntityACL\Controller\Helper\Acl.php
 */
class ACLHelperException extends \Exception{
    public function __construct($message, $code = 0, \Exception $previous = null) {
        parent::__construct($message, $code, $previous);
        if($previous){
            $this->message = $this->getMessage() .'<br/>'. $this->getPrevious()->getMessage();
            $this->code = $this->getCode() . '.' . $this->getPrevious()->getCode();
        }
    }
    public function __toString() {
        return __CLASS__ . ": [{$this->code}.{$this->getPrevious()->getCode()}]: {$this->getPrevious()->getMessage()} \n";
    }
}


