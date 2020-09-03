# w3b-log
A simple PHP logger that implements the PSR standard.

## Usage

### Load the interfaces and logger class
Make sure the filepaths are correct and are included in this order.
```php
    require 'Psr/Log/LogLevel.php';
    require 'Psr/Log/LoggerInterface.php';
    require 'Psr/Log/invalidArgumentException.php';
    require 'w3b-log.inc.php';
```

### Initialize the logger
Use the standard filename "w3b.log" ...
```php
    $log = new LOG();
```
... or use the constructor with a given filename.
```php
    $log = new LOG("filename.log");
```

### Log a message
Now you can use the logger to log a message into a certain level.
```php
    $log->debug("message");
    $log->info("message");
    $log->notice("message");
    $log->warning("message");
    $log->error("message");
    $log->critical("message");
    $log->alert("message");
    $log->emergency("message");
```
