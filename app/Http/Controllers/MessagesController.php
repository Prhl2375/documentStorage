<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class MessagesController extends Controller
{
    public function index(): View
    {
        $path = storage_path('logs/deletions.log');
        $messages = [];

        if (file_exists($path)) {
            $lines = array_filter(explode("\n", file_get_contents($path)));
            foreach (array_reverse($lines) as $line) {
                $decoded = json_decode($line, true);
                if ($decoded) {
                    $messages[] = $decoded;
                }
            }
        }

        return view('messages', ['messages' => $messages]);
    }
}
