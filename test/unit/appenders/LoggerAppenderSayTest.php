<?php

/**
 * Special for mac cool appender!
 */
class LoggerAppenderSayTest extends PHPUnit_Framework_TestCase
{
    public function testWrite()
    {
        $appender = new LoggerAppenderSay();
        $appender->write(1, 'fuck, web site is down!');
    }
}