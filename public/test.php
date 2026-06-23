<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    $xendit = app(App\Services\XenditService::class);
    $invoice = $xendit->createInvoice(
        'TEST-' . time(),
        11000, // amount mismatch
        'Test Booking',
        [
            'given_names'   => 'Test User',
            'email'         => 'test@example.com',
            'mobile_number' => '08123456789',
            'items'         => [
                [
                    'name'     => 'Experience',
                    'quantity' => 1,
                    'price'    => 10000,
                ],
            ],
        ],
        'http://localhost/success',
        'http://localhost/fail'
    );
    echo "SUCCESS: " . $invoice['invoice_url'];
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
