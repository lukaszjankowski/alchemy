<?php
namespace Alchemy;
use \Alchemy\Model\Exception as ModelException;

abstract class ModelFacade
{
    /**
     * Name of related model
     *
     * @var \Alchemy\Model
     */
    protected $model;

    /**
     * Service result
     *
     * @var mixed
     */
    private $result;

    /**
     * Errors from model
     *
     * @var array
     */
    private $error = array();

    /**
     * @return \Alchemy\Model
     */
    public function getModel()
    {
        if(!is_null($this->model))
        {
            return $this->model;
        }

        $className = '\\Alchemy\\Model\\' . $this->getModelName();
        \Zend_Loader::loadClass($className);
        $this->model = new $className;

        return $this->model;
    }

    /**
     * @param \Alchemy\Model $model
     */
    public function setModel(\Alchemy\Model $model)
    {
        $this->model = $model;
    }

    protected function setResult($result)
    {
        $this->result = $result;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param string $method
     * @param array $args
     * @throws ModelException
     * @return boolean
     */
    public function __call($method, array $args = array())
    {
        if(!method_exists($this->getModel(), $method))
        {
            throw new ModelException("Unknown method '" . $this->getModelName() . "::$method()'");
        }

        try
        {
            $response = call_user_func_array(
                array(
                    $this->getModel(),
                    $method
                ), $args);
            $this->setResult($response);
            return true;
        }
        catch(ModelException $e)
        {
            $this->errorHelper($e);
            return false;
        }
    }

    /**
     * @param ModelException $e
     */
    private function errorHelper(ModelException $e)
    {
        $this->error = array(
            'message' => $e->getMessage()
        );
    }

    /**
     * @return array
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Return name of related model
     *
     * @return string
     */
    abstract protected function getModelName();

}
