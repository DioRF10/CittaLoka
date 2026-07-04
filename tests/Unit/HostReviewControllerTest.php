<?php

namespace Tests\Unit;

use App\Http\Controllers\Host\HostReviewController;
use PHPUnit\Framework\TestCase;

class HostReviewControllerTest extends TestCase
{
    public function test_host_review_controller_exposes_the_expected_actions(): void
    {
        $controller = new HostReviewController();

        $this->assertTrue(method_exists($controller, 'index'));
        $this->assertTrue(method_exists($controller, 'reply'));
    }
}
