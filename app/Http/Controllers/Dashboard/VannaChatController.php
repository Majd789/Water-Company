<?php
namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\StreamedResponse; // تأكد من استيراد هذه الفئة

class VannaChatController extends Controller
{
    public function index()
    {
        return view('vanna.chat');
    }

    public function stream(Request $request)
    {
        $question = $request->input('question');

        // يُنصح بشدة بتخزين مفتاح API الخاص بك في ملف .env
        // والوصول إليه باستخدام env('VANNA_API_KEY') أو config('services.vanna.key')
        $vannaApiKey = env('VANNA_API_KEY', 'vn-b260d770df8e44508a922ab225a64bc6');

      // ...
// في VannaChatController.php، داخل دالة stream
        $client = new \GuzzleHttp\Client();
        $response = $client->post('https://api.vanna.ai/api/v0/chat_sse', [ // إعادة إلى POST
            'headers' => [
                'Content-Type' => 'application/json',
                'VANNA-API-KEY' => $vannaApiKey,
            ],
            'json' => [ // استخدم 'json' لطلب POST
                'message' => $question,
                'user_email' => 'ghazehallakkhalel@gmail.com',
                'acceptable_responses' => ['text', 'end', 'error', 'image', 'link', 'buttons', 'dataframe', 'plotly', 'sql']
            ],
            'stream' => true,
        ]);
        return new StreamedResponse(function () use ($response) {
            $body = $response->getBody();
            while (!$body->eof()) {
                echo $body->read(1024); // قراءة وإخراج أجزاء من البث
                ob_flush(); // تفريغ مخزن الإخراج المؤقت لـ PHP
                flush();    // تفريغ مخزن النظام المؤقت
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
        ]);
    }
}
