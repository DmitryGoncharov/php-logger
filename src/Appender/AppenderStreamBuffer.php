<?php

namespace Mougrim\Logger\Appender;

use Mougrim\Logger\Logger;

/**
 * Class logger AppenderStreamBuffer implements log buffer with threshold.
 *
 * If appender have event with level less than threshold, it puts event to in memory buffer.
 * If appender have event with level greater or equal than threshold, it puts buffered messages to stream.
 *
 * Example of use:
 * <pre>
 * use Mougrim\Logger\Appender\AppenderStreamBuffer;
 *
 * $appender = new AppenderStreamBuffer('php://stdout');
 * $appender->setThreshold(Logger::ERROR);
 * $appender->append($logger, Logger::DEBUG, 'debug'); // no output
 * $appender->append($logger, Logger::INFO, 'info');   // no output
 * $appender->append($logger, Logger::ERROR, 'error'); // outputs "debug\ninfo\nerror\n"
 * </pre>
 */
class AppenderStreamBuffer extends AppenderStream
{
    private $buffer;
    private $threshold = Logger::ERROR;

    public function __construct($stream)
    {
        $this->buffer = new \SplDoublyLinkedList();
        parent::__construct($stream);
    }

    public function append(Logger $logger, $level, $message, \Exception $throwable = null)
    {
        if ($this->minLevel !== null && $level < $this->minLevel) {
            return;
        }
        if ($this->maxLevel !== null && $level > $this->maxLevel) {
            return;
        }
        if ($this->layout) {
            $message = $this->layout->formatMessage($logger, $level, $message, $throwable);
        }
        $this->buffer->push($message);
        if ($level >= $this->threshold) {
            while (!$this->buffer->isEmpty() && ($message = $this->buffer->shift())) {
                $this->write($level, $message);
            }
        }
    }

    public function setThreshold($threshold)
    {
        $this->threshold = (int) $threshold;
    }
}
