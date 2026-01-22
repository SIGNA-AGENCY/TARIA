<?php

if (!defined('TARIA_ROOT')) {
    define('TARIA_ROOT', dirname(__DIR__, 2));
}

if (!defined('TARIA_CORE')) {
    define('TARIA_CORE', TARIA_ROOT . '/core');
}

if (!defined('TARIA_ENGINE')) {
    define('TARIA_ENGINE', TARIA_ROOT . '/engine');
}

if (!defined('TARIA_CONFIG')) {
    define('TARIA_CONFIG', TARIA_ROOT . '/config');
}

if (!defined('TARIA_STORAGE')) {
    define('TARIA_STORAGE', TARIA_ROOT . '/storage');
}

if (!defined('TARIA_PUBLIC')) {
    define('TARIA_PUBLIC', TARIA_ROOT . '/public');
}
