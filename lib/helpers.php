<?php
spl_autoload_register('autoLoader');

/**
 * Method for autoload all the classes within directory.
 *
 * @param class-string $class
 * @param string|null  $dir
 *
 * @return void
 */
function autoLoader($class, $dir = null)
{
    $namespace = 'Valitor';
    require_once __DIR__.DIRECTORY_SEPARATOR.'IValitorCommunicationLogger.class.php';

    if (0 !== strpos($class, $namespace)) {
        return;
    }

    if ($dir === null) {
        $dir = __DIR__;
    }
    //Load the Valitor SDK version
    //TODO: refactor this
    include_once $dir.DIRECTORY_SEPARATOR.'VALITOR_VERSION.php';

    $listDir = scandir(realpath($dir));
    if (!empty($listDir)) {
        foreach ($listDir as $listDirkey => $subDir) {
            if ($subDir == '.' || $subDir == '..') {
                continue;
            }
            $file = $dir.DIRECTORY_SEPARATOR.$subDir.DIRECTORY_SEPARATOR.$class.'.class.php';
            if (file_exists($file)) {
                require_once $file;
                break;
            }
        }
    }
}
