<?php

namespace PHPUnitNotifier;

use Joli\JoliNotif\Notification;
use Joli\JoliNotif\NotifierFactory;
use Joli\JoliNotif\Util\OsHelper;
use PHPUnit_Framework_Test as Test;
use PHPUnit_Framework_TestSuite as TestSuite;
use PHPUnit_Framework_AssertionFailedError as AssertionFailedError;

class NotifierListener extends \PHPUnit_Framework_BaseTestListener
{
    private $notifier;
    private $errors = 0;
    private $failures = 0;
    private $tests = 0;
    private $suites = 0;
    private $ended_suites = 0;
    private static $is_darwin;

    public function __construct()
    {
        $this->notifier = NotifierFactory::create();
        if (self::$is_darwin === null) {
            self::$is_darwin = OsHelper::isMacOS();
        }
    }

    public function addError(Test $test, \Exception $e, $time)
    {
        $this->errors++;
    }

    public function addFailure(Test $test, AssertionFailedError $e, $time)
    {
        $this->failures++;
    }

    public function startTestSuite(TestSuite $suite)
    {
        $this->suites++;
    }

    public function endTestSuite(TestSuite $suite)
    {
        $this->ended_suites++;

        if ($this->suites > $this->ended_suites) {
            return;
        }

        $failures = $this->errors + $this->failures;
        if ($failures === 0) {
            $title = sprintf('%sSuccess', self::$is_darwin ? '✅ ' : '');
            $body  = sprintf('%d/%d tests passed', $this->tests, $this->tests);
        } else {
            $title = sprintf('%sFailed', self::$is_darwin ? '🚫 ' : '');
            $body  = sprintf('%d/%d tests failed', $failures, $this->tests);
        }

        $notification = (new Notification())
            ->setTitle($title)
            ->setBody($body)
        ;
        $this->notifier->send($notification);
    }

    public function startTest(Test $test)
    {
        $this->tests++;
    }
}
