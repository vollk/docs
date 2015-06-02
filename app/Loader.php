<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Vollk
 * Date: 01/06/15
 * Time: 18:41
 */

class ClassLoader {

    public function __construct() {
        spl_autoload_register(array($this, 'loader'));
    }
    private function loader($className) {
        $paths = array(
            'models',
            'template_engine'
        );

        foreach($paths as $path)
        {
            $file = Application::$appPath.DIRECTORY_SEPARATOR.$path.DIRECTORY_SEPARATOR.$className .'.php';
            if(file_exists($file))
            {
                include $file;
            }
        }
    }
}