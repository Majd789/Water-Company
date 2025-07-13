<?php

namespace App\Livewire;

use Livewire\Component;
use Gemini\Laravel\Facades\Gemini;
use Exception;

class ChatBot extends Component
{
    public array $conversationHistory = [];
    public string $input = '';

    public function mount(): void
    {
        $this->conversationHistory[] = [
            'sender' => 'bot',
            'text'   => 'مرحباً! أنا خليل، مساعدك الذكي. كيف يمكنني مساعدتك اليوم؟'
        ];
    }

    public function send(): void
    {
        if (trim($this->input) === '') {
            return;
        }

        // أضف رسالة المستخدم إلى السجل
        $userMessage = trim($this->input);
        $this->conversationHistory[] = ['sender' => 'user', 'text' => $userMessage];
        $this->reset('input');

        try {
            // بناء برومبت باللغة العربية يتضمّن سجل المحادثة
            $chatHistory = collect($this->conversationHistory)
                ->map(fn ($msg) => ($msg['sender'] === 'bot' ? 'خليل: ' : 'المستخدم: ') . $msg['text'])
                ->implode("\n");

            $prompt = "أنت مساعد ذكي يُدعى «خليل». يجب أن ترد دائماً باللغة العربية فقط.\n"
                    . "سجل المحادثة السابق:\n"
                    . $chatHistory
                    . "\n\nالآن أجب على آخر رسالة للمستخدم بصورة ودودة ومفيدة.";

            // استدعاء Gemini
            $response = Gemini::generativeModel('gemini-1.5-flash-latest')
                ->generateContent($prompt);

            $botReply = $response->text() ?? 'عذراً، لم أفهم سؤالك.';

        } catch (Exception $e) {
            $botReply = 'عذراً، حدث خطأ تقني. حاول مرة أخرى لاحقاً.';
        }

        // أضف رد البوت إلى السجل
        $this->conversationHistory[] = ['sender' => 'bot', 'text' => $botReply];

        // لإعادة رسم الواجهة في Livewire
        $this->dispatch('message-sent-and-received');
    }

    public function render()
    {
        return view('livewire.chat-bot', [
            'messages' => $this->conversationHistory,
        ])->layout('layouts.app');
    }
}
