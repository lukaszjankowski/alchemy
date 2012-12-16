<?php
namespace Alchemy\Model;
use \Alchemy\Model\Exception as ModelException;

class Facade
{
    /**
     * Name of related model
     *
     * @var \Alchemy\Model
     */
    private $model;

    /**
     * Service result
     *
     * @var mixed
     */
    private $result;

    /**
     * Error from model
     *
     * @var array
     */
    private $error = array();

    /**
     * @param \Alchemy\Model $model
     */
    public function __construct(\Alchemy\Model $model)
    {
        $this->model = $model;
    }

    /**
     * @param string $modelName
     * @return \Alchemy\Model
     */
    public function getModel($modelName)
    {
        return $this->model;
    }

    /**
     * @param mixed $result
     */
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
        if(!method_exists($this->model, $method))
        {
            throw new ModelException("Unknown method '" . get_class($this->model) . "::$method()'");
        }

        try
        {
            $response = call_user_func_array(
                array(
                    $this->model,
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

}
