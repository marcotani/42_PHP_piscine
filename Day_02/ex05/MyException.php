<?php

    class MyException extends Exception
    {
        public function __construct($message, $code = 0, Exception $previous = null)
        {
            parent::__construct($message, $code, $previous);
        }

        public function getErrorMessage()
        {
            return "Error on line {$this->getLine()} in {$this->getFile()}: {$this->getMessage()} - MA COSA FAIIII???\n";
        }
    }

?>