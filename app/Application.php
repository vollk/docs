<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Vollk
 * Date: 01/06/15
 * Time: 18:03
 */

class Application extends Silex\Application
{
    public static  $appPath ;
    public static  $templatePath;
    public static  $docsPath;
    private static $instance;
    public function __construct(array $values = array())
    {
        parent::__construct($values);
        $this->initPaths();
        $this->initDatabase();
        $this->initAutoload();
        $this->initParamManager();
    }

    public static function getInstance()
    {
        if(is_null(self::$instance))
        {
            self::$instance = new self;
        }
        return self::$instance;
    }
    public function initPaths()
    {
        self::$appPath = dirname(__FILE__);
        self::$templatePath = dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'templates';
        self::$docsPath = 'docs';
    }

    public function initDatabase()
    {
        $config = new \Doctrine\DBAL\Configuration();
        $connectionParams = include self::$appPath.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'config.php';

        $conn = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);
        $conn->query("set names utf8")->execute();
        $this['db'] = $conn;
    }

    public function initAutoload()
    {
        require_once(self::$appPath.DIRECTORY_SEPARATOR.'Loader.php' );
        new ClassLoader();
    }

    public function initParamManager()
    {
        require_once(self::$appPath.DIRECTORY_SEPARATOR.'ParamManager.php' );
        $this['paramManager'] = new ParamManager();
    }


    /**
     * @param $name
     * @return BaseModel
     * @throws Exception
     */
    public function createModel($name)
    {
        $class_name = $name.'Model';
        if(class_exists($class_name))
        {
            $model = new $class_name;
        }
        else
            throw new Exception('model '.$class_name.' not found');
        /**
         * @var $model BaseModel
         */
        $model->setDb($this['db']);
        return $model;
    }


}