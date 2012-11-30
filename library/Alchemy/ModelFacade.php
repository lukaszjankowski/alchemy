<?php
namespace Alchemy;

use Alchemy\Model\Exception;

abstract class ModelFacade
{
    /**
     * Name of related model
     *
     * @var		\Alchemy\Model
     */
    protected $model;

    /**
     * Service result
     *
     * @var		mixed
     */
    private $result;

    /**
     * Should every service invocation throw an exception? E.g. for testing purposes
     *
     * @var    boolean
     */
    private static $throwsExceptionAtEveryCall = false;

    /**
     * Errors from model
     *
     * @var    array
     */
    private $errors = array();

    public function __construct()
    {
        $className = 'Alchemy\\Model\\' . $this->getModelName();
        \Zend_Loader::loadClass($className);
        $this->model = new $className;
    }

    protected function setResult($result)
    {
        $this->result = $result;
    }

    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param    boolean $flag
     */
    public static function throwsExceptionAtEveryCall($flag)
    {
        self::$throwsExceptionAtEveryCall = $flag;
    }

    public function __call($method, $args = array())
    {
        if(!method_exists($this->model, $method))
        {
            throw new Exception("Unknown method '" . $this->getModelName() . "::$method()'");
        }

        try
        {
            if(self::$throwsExceptionAtEveryCall)
            {
                throw new Exception('An exception thrown because of self::$throwsExceptionAtEveryCall');
            }

            $response = call_user_func_array(
                array(
                    $this->model,
                    $method
                ), $args);
            $this->setResult($response);
            return true;
        }
        catch(Exception $e)
        {
            $this->errorHelper($e);
            return false;
        }
    }

    /**
     * @param    Exception $e
     */
    private function errorHelper(Exception $e)
    {
        $this->errors[] = array(
            'message' => $e->getMessage()
        );
    }

    /**
     * @return    array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Return name of related model
     *
     * @return	string
     */
    abstract protected function getModelName();

}
