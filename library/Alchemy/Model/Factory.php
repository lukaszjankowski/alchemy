<?php
namespace Alchemy\Model;
class Factory
{
    /**
     * @var \Alchemy\Model\Factory
     */
    private static $instance;

    /**
     * @var array
     */
    private $models = array();

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    /**
     * @return \Alchemy\Model\Factory
     */
    public static function getInstance()
    {
        if(!is_null(self::$instance))
        {
            return self::$instance;
        }

        self::$instance = new self;
        return self::$instance;
    }

    /**
     * @param string $modelName
     * @return \Alchemy\Model\Facade
     */
    public function getModel($modelName)
    {
        if(isset($this->models[$modelName]))
        {
            return $this->models[$modelName];
        }

        $className = '\\Alchemy\\Model\\Service\\' . $modelName;
        $facade = new Facade(new $className);
        $this->models[$modelName] = $facade;

        return $this->models[$modelName];
    }

    /**
     * @param string $modelName
     * @param Facade $model
     */
    public function setModel($modelName, Facade $model)
    {
        $this->models[$modelName] = $model;
    }

    public function reset()
    {
        $this->models = array();
    }

}
