<?php

namespace PHPUnitNotifier;

use Joli\JoliNotif\Notification;
use Joli\JoliNotif\NotifierFactory;
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

    public function __construct()
    {
        $this->notifier = NotifierFactory::create();
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

        $notification = new Notification();

        $failures = $this->errors + $this->failures;
        if ($failures === 0) {
            $notification
                ->setTitle('Tests passed')
                ->setBody(sprintf('%d/%d tests', $this->tests, $this->tests))
            ;
        } else {
            $notification
                ->setTitle('Tests failed')
                ->setBody(sprintf('%d/%d tests failed', $failures, $this->tests))
            ;
        }

        $this->notifier->send($notification);
    }

    public function startTest(Test $test)
    {
        $this->tests++;
    }
}
