<?php

/**
 * Initialisations
 *
 * Register an autoloader, start or resume the session etc.
 */

spl_autoload_register(function ($class) {
    require dirname(__DIR__) . "/classes/{$class}.php";
});


/**
 * Error and exception handling
 */
function errorHandler($level, $message, $file, $line)
{
    throw new ErrorException($message, 0, $level, $file, $line);
}

function exceptionHandler($exception)
{
    http_response_code(500);
    
    if (SHOW_ERROR_DETAIL) {

        echo "<h1>An error occurred</h1>";
        echo "<p>Uncaught exception: '" . get_class($exception) . "'</p>";
        echo "<p>" . $exception->getMessage() . "'</p>";
        echo "<p>Stack trace: <pre>" . $exception->getTraceAsString() . "</pre></p>";
        echo "<p>In file '" . $exception->getFile() . "' on line " . $exception->getLine() . "</p>";

    } else {

        echo "<h1>An error occurred</h1>";
        echo "<p>Please try again later.</p>";

    }

    exit();
}

set_error_handler('errorHandler');
set_exception_handler('exceptionHandler');
