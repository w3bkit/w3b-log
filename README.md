# w3b-log
A simple PHP logger that implements the PSR standard.

## Usage

### Initialize the logger
```php
    require 'Psr/Log/LogLevel.php';
    require 'Psr/Log/LoggerInterface.php';
    require 'Psr/Log/invalidArgumentException.php';
    require 'w3b-log.inc.php';
    $log = new LOG();
```

### Log a message
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
