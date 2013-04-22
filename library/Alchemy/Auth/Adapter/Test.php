<?php
namespace Alchemy\Auth\Adapter;

/**
 * An auth adapter for tests purposes
 */
class Test implements \Zend_Auth_Adapter_Interface
{
    const DEFAULT_USERNAME = 'lukasz';
    const DEFAULT_PASSWORD = 'qw12qw';

    /**
     * $identity - Identity value
     *
     * @var string
     */
    protected $identity = null;

    /**
     * $credential - Credential values
     *
     * @var string
     */
    protected $credential = null;

    /**
     * @var array
     */
    private $authenticateResultInfo = array();

    /**
     * setIdentity() - set the value to be used as the identity
     *
     * @param  string                     $value
     * @return \Zend_Auth_Adapter_DbTable Provides a fluent interface
     */
    public function setIdentity($value)
    {
        $this->identity = $value;

        return $this;
    }

    /**
     * setCredential() - set the credential value to be used, optionally can specify a treatment
     * to be used, should be supplied in parameterized form, such as 'MD5(?)' or 'PASSWORD(?)'
     *
     * @param  string                     $credential
     * @return \Zend_Auth_Adapter_DbTable Provides a fluent interface
     */
    public function setCredential($credential)
    {
        $this->credential = $credential;

        return $this;
    }

    public function authenticate()
    {
        $this->authenticateSetup();
        if (empty($this->identity) || empty($this->credential)) {
            throw new \Zend_Auth_Adapter_Exception('');
        }

        if ($this->identity != self::DEFAULT_USERNAME || $this->credential != self::DEFAULT_PASSWORD) {
            return new \Zend_Auth_Result(\Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID, $this->identity);
        }

        return new \Zend_Auth_Result(\Zend_Auth_Result::SUCCESS, $this->identity);
    }

    /**
     * authenticateSetup() - This method abstracts the steps involved with
     * making sure that this adapter was indeed setup properly with all
     * required pieces of information.
     *
     * @throws \Zend_Auth_Adapter_Exception - in the event that setup was not done properly
     * @return true
     */
    protected function authenticateSetup()
    {
        $exception = null;

        if ($this->identity == '') {
            $exception = 'A value for the identity was not provided prior'
                . ' to authentication with \Zend_Auth_Adapter_DbTable.';
        } elseif ($this->credential === null) {
            $exception = 'A credential value was not provided prior to authentication with \Zend_Auth_Adapter_DbTable.';
        }

        if (null !== $exception) {
            /**
             * @see \Zend_Auth_Adapter_Exception
             */
            require_once 'Zend/Auth/Adapter/Exception.php';
            throw new \Zend_Auth_Adapter_Exception($exception);
        }

        $this->authenticateResultInfo = array(
            'code' => \Zend_Auth_Result::FAILURE,
            'identity' => $this->identity,
            'messages' => array()
        );

        return true;
    }

    /**
     * @return array
     */
    public function getAuthenticateResultInfo()
    {
        return $this->authenticateResultInfo;
    }
}
