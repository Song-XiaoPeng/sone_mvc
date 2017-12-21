<?php
spl_autoload_register('autoloader');

function autoloader($className)
{
    echo '需要' . $className;
}