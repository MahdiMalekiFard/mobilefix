<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Events\MessageSent;
use App\Models\Message;
use App\Helpers\BroadcastHelper;

echo "Testing broadcasting...\n";

// Check if broadcasting is available
echo "Broadcasting available: " . (BroadcastHelper::isAvailable() ? 'Yes' : 'No') . "\n";

// Try to find a message to test with
$message = Message::first();
if ($message) {
    echo "Found message ID: " . $message->id . "\n";
    echo "Testing broadcast...\n";
    
    $result = BroadcastHelper::safeBroadcast(new MessageSent($message));
    echo "Broadcast result: " . ($result ? 'Success' : 'Failed') . "\n";
} else {
    echo "No messages found in database\n";
}

echo "Test completed.\n";
