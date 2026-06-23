<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$request = new \Illuminate\Http\Request();
$request->merge([
    'guests' => 1,
    'phone_number' => '08123456789',
    'agree_terms' => true,
]);

$availability = \App\Models\ExperienceAvailability::first();
if (!$availability) {
    echo "No availability found.\n";
    exit;
}
$experience = $availability->experience;
$experience->update(['status' => 'active']);

$user = \App\Models\User::where('id', '!=', $experience->host->user_id)->first();
\Illuminate\Support\Facades\Auth::login($user);

$request->merge(['availability_id' => $availability->id]);

$controller = app(\App\Http\Controllers\CheckoutController::class);
try {
    $response = $controller->store($request, $experience->slug);
    if (get_class($response) === 'Illuminate\Http\RedirectResponse') {
        echo "REDIRECT: " . $response->getTargetUrl();
        $session = session()->all();
        if (isset($session['error'])) {
            echo "\nERROR FLASH: " . $session['error'];
        }
    }
} catch (\Throwable $e) {
    echo "EXCEPTION: " . $e->getMessage() . "\n" . $e->getTraceAsString();
}
