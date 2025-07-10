<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Gemini\Laravel\Facades\Gemini;
use Exception;

class GeminiChat extends Command
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'chat:gemini';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'ابدأ محادثة تفاعلية مع Gemini AI في سطر الأوامر';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("💬 Gemini AI Chatbot. (اضغط Enter على سطر فارغ للخروج)");
        $this->info("======================================================");

        try {
            // *** تم تعديل هذا السطر لاستخدام موديل أحدث ومتاح ***
            $chat = Gemini::generativeModel('gemini-1.5-flash-latest')->startChat();

            // حلقة لا نهائية للاستمرار في الدردشة
            while (true) {
                // اطلب من المستخدم إدخال رسالة
                $input = $this->ask('أنت');

                // شرط الخروج: إذا ضغط المستخدم على Enter بدون كتابة شيء
                if (trim($input) === '') {
                    $this->info('👋 تم إنهاء المحادثة. إلى اللقاء!');
                    break; // اخرج من الحلقة
                }

                // أرسل رسالة المستخدم إلى Gemini واحصل على الرد
                $response = $chat->sendMessage($input);

                // اطبع رد Gemini على الشاشة
                $this->line("🤖 Gemini: " . $response->text());
            }

        } catch (Exception $e) {
            // في حالة وجود خطأ
            $this->error("حدث خطأ فادح: " . $e->getMessage());
            $this->error("يرجى التأكد من أن مفتاح GEMINI_API_KEY صحيح وأن الموديل متاح.");
            return 1; // إنهاء الأمر مع رمز خطأ
        }

        return 0; // إنهاء الأمر بنجاح
    }
}