<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/27 0027
 * Time: 10:44
 */
namespace autoload;
use star;
use server;
class Autoload{

    private static $rootPath;
    private static $appPath='app';
    private static $configPath;
    private static $classPath = array();


    public static function getRootPath() {
        return self::$rootPath;
    }
    final public static function autoLoader($class)
    {
        if(isset(self::$classPath[$class])) {
            require self::$classPath[$class];
            return;
        }
        $baseClasspath = \str_replace('\\', DS, $class) . '.class.php';
        $libs = array(
            self::$rootPath . DS . self::$appPath,
            self::$rootPath
        );
        foreach ($libs as $lib) {
            $classpath = $lib . DS . $baseClasspath;
            if (\is_file($classpath)) {
                self::$classPath[$class] = $classpath;
                require "{$classpath}";
                return;
            }
        }
    }

    final public static function exceptionHandler($exception)
    {
        $exceptionHash = array(
            'className' => 'Exception',
            'message' => $exception->getMessage(),
            'code' => $exception->getCode(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'userAgent' => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '',
            'trace' => array(),
        );
        print_r($exceptionHash);
    }

    public static function run($rootPath) {
        self::$rootPath = $rootPath;
        if (!defined('DS')) {
            define('DS', DIRECTORY_SEPARATOR);
        }
        \spl_autoload_register(__CLASS__ . '::autoLoader');
        \set_exception_handler(__CLASS__ . '::exceptionHandler');

        $star = new \star\Star();
        $star::run();
    }
}
