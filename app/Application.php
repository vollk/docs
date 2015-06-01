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

    public function __construct(array $values = array())
    {
        parent::__construct($values);
        $this->initPaths();
        $this->initDatabase();
        $this->initAutoload();
    }

    public function initPaths()
    {
        self::$appPath = dirname(__FILE__);
        self::$templatePath = dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'templates';
        self::$docsPath = dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'www/docs';
    }

    public function initDatabase()
    {
        $config = new \Doctrine\DBAL\Configuration();
        $connectionParams = include '../app/config/config.php';

        $conn = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);
        $conn->query("set names utf8")->execute();
        $this['db'] = $conn;
    }

    public function initAutoload()
    {
        require_once(self::$appPath.DIRECTORY_SEPARATOR.'Loader.php' );
        new ClassLoader();
    }

    public function createModel($name)
    {
        $class_name = $name.'Model';
        if(class_exists($class_name))
        {
            return new $class_name;
        }
        else
            throw new Exception('model '.$class_name.' not found');
    }
}