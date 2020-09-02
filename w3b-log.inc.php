<?php

    use Psr\Log\LogLevel;
    use Psr\Log\LoggerInterface;
    use Psr\Log\InvalidArgumentException;

    /**
     * 
     */
    class LOG implements LoggerInterface {

        /**
         * Holds the separation character, used in the log string.
         * @var string
         */
        private $separation;

        /**
         * Holds the new line character, used in the log string.
         * @var string
         */
        private $newline;

        /**
         * Holds the file handler for the logfile.
         * @var Ressource
         */
        private $file;

        /**
         * Holds the pattern for the timestamp.
         * @var string
         */
        private $pattern;

        /**
         * Class constructor.
         */
        public function __construct($filename='w3b.log') {
            $this->timezone('Europe/Vienna');
            $this->separation("\t");
            $this->newline = $this->detectNewLine();
            $this->file = fopen($filename, 'ab');
            $this->pattern = 'Y-m-d H:i:s';
        }

        /**
         * Class destructor. Closes the logfile, if there is one.
         */
        public function __destruct() {
            if($this->file) fclose($this->file);
        }

        /**
         * 
         */
        public function __toString() {
            return '';
        }

        /**
         * Detects the operation system and returns the respective new line character.
         * @return string The appropriate new line character.
         */
        private function detectNewLine() {
            return substr(php_uname('s'), 0, 3) == 'Win' ? "\r\n" : "\n";
        }

        /**
         * Getter / Setter for the timezone.
         * @param string $zone The timezone to set.
         * @return string The timezone to get.
         */
        public function timezone($zone='') {
            if($zone) @ date_default_timezone_set($zone);
            else return date_default_timezone_get();
        }

        /**
         * Getter / Setter for the separation character.
         * @param string The separation character to set.
         * @return string The separation character to get.
         */
        public function separation($separation='') {
            if($separation) $this->separation = $separation;
            else return $this->separation;
        }

        /**
         * Setter for the pattern , used for the timestamp.
         * @param string The wished pattern.
         */
        public function pattern($pattern) {
            $this->pattern = $pattern;
        }

        /**
         * Creates a timestamp from the saved pattern.
         * @return string The timesstamp used for the log message.
         */
        private function timestamp() {
            return '['.date($this->pattern).']';
        }

        /**
         * https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md
         */
        private function interpolate($message, $context) {
            // build a replacement array with braces around the context keys
            $replace = array();
            foreach ($context as $key => $val) {
                // check that the value can be cast to string
                if (!is_array($val) && (!is_object($val) || method_exists($val, '__toString'))) {
                    $replace['{' . $key . '}'] = $val;
                }
            }
            // interpolate replacement values into the message and return
            return strtr($message, $replace);
        }

        /**
         * Builds a message string to log into the file.
         * @param string
         * @param string
         * @param array
         */
        private function message($level, $message, $context) {
            return ''
            . $this->timestamp()
            . $this->separation
            . '['. $level.']'
            . $this->separation
            . basename(debug_backtrace()[2]['file'])
            . ':'
            . debug_backtrace()[2]['line']
            . $this->separation
            . $this->interpolate($message, $context)
            . $this->newline
            ;
        }

        /**
         * 
         */
        public function emergency($message, array $context = array()) {
            $this->log(LogLevel::EMERGENCY, $message, $context);
        }

        /**
         * 
         */
        public function alert($message, array $context = array()) {
            $this->log(LogLevel::ALERT, $message, $context);
        }

        /**
         * 
         */
        public function critical($message, array $context = array()) {
            $this->log(LogLevel::CRITICAL, $message, $context);
        }

        /**
         * 
         */
        public function error($message, array $context = array()) {
            $this->log(LogLevel::ERROR, $message, $context);
        }

        /**
         * 
         */
        public function warning($message, array $context = array()) {
            $this->log(LogLevel::WARNING, $message, $context);
        }

        /**
         * 
         */
        public function notice($message, array $context = array()) {
            $this->log(LogLevel::NOTICE, $message, $context);
        }

        /**
         * 
         */
        public function info($message, array $context = array()) {
            $this->log(LogLevel::INFO, $message, $context);
        }

        /**
         * 
         */
        public function debug($message, array $context = array()) {
            $this->log(LogLevel::DEBUG, $message, $context);
        }

        /**
         * 
         */
        public function log($level, $message, array $context = array()) {

            $reflect = new ReflectionClass(new LogLevel());
            if(!array_key_exists(strtoupper($level), $reflect->getConstants())) throw new InvalidArgumentException();

            if($this->file) fwrite($this->file, $this->message($level, $message, $context));
        }

    }

?>