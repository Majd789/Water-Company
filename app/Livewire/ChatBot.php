<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Station;
use App\Models\Well;
use App\Models\GenerationGroup;
use App\Models\Governorate;
use Gemini\Laravel\Facades\Gemini;
use Exception;

class ChatBot extends Component
{
    public array $conversationHistory = [];
    public string $input = '';

    public function mount()
    {
        $this->conversationHistory[] = [
            'sender' => 'bot',
            'text' => 'مرحباً! أنا مساعد نظام مراقبة المياه. يمكنك أن تسألني عن حالة المحطات، عدد الآبار، أو تقارير شاملة.'
        ];
    }

    // ===================================================================
    // دالة: جلب تقرير شامل عن محطة
    // ===================================================================
    private function getStationFullReport(string $stationName): string
    {
        if (empty($stationName)) {
            return "خطأ: يرجى تحديد اسم المحطة.";
        }

        $station = Station::with(['wells', 'generationGroups', 'unit.governorate'])
            ->whereRaw('LOWER(station_name) LIKE ?', ['%' . strtolower($stationName) . '%'])
            ->first();

        if (!$station) {
            return "خطأ: لم أتمكن من العثور على محطة بالاسم '{$stationName}'.";
        }

        $governorateName = $station->unit?->governorate?->name ?? 'غير محدد';
        $unitName = $station->unit?->unit_name ?? 'غير محدد';

        $report = "--- تقرير شامل لمحطة {$station->station_name} ---\n";
        $report .= "الموقع: محافظة {$governorateName} - وحدة مياه {$unitName}\n";
        $report .= "--------------------------------------\n";
        $report .= "عدد الآبار الإجمالي: " . $station->wells->count() . "\n";
        $report .= "عدد المولدات: " . $station->generationGroups->count() . "\n\n";

        if ($station->wells->count() > 0) {
            $report .= "**تفاصيل الآبار:**\n";
            foreach ($station->wells as $well) {
                $report .= "- {$well->well_name} (الحالة: {$well->well_status}, النوع: {$well->well_type})\n";
            }
        }

        if ($station->generationGroups->count() > 0) {
            $report .= "\n**تفاصيل المولدات:**\n";
            foreach ($station->generationGroups as $generator) {
                $report .= "- {$generator->generator_name} (الحالة: {$generator->operational_status}, السعة: {$generator->generation_capacity} ك.ف.أ)\n";
            }
        }

        return $report;
    }

    // ===================================================================
    // دالة: حساب عدد الآبار بحالة معينة
    // ===================================================================
    private function countWellsByStatus(string $status): int
    {
        $dbStatus = (str_contains($status, 'تعمل') || str_contains($status, 'يعمل')) ? 'يعمل' : 'متوقف';
        return Well::where('well_status', $dbStatus)->count();
    }

    // ===================================================================
    // دالة: جلب قائمة محطات بمحافظة
    // ===================================================================
    private function listStationsInGovernorate(string $governorateName): array
    {
        $gov = Governorate::whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($governorateName) . '%'])->first();

        if (!$gov) return [];

        return Station::whereHas('unit', function ($query) use ($gov) {
            $query->where('governorate_id', $gov->id);
        })->pluck('station_name')->toArray();
    }

    // ===================================================================
    // الدالة الرئيسية
    // ===================================================================
   public function send()
    {
        if (trim($this->input) === '') return;

        $userMessage = $this->input;
        $this->conversationHistory[] = ['sender' => 'user', 'text' => $userMessage];
        $this->reset('input');

        try {
            $botResponseText = '';
            $promptForGemini = '';
            $isHandledLocally = false;

            // --- منطقة تحليل نية المستخدم ---

            // الحالة 1: تقرير عن محطة
            if (preg_match('/(تقرير|تفاصيل) عن محطة (.*)/u', $userMessage, $matches)) {
                $stationName = trim($matches[2]);
                $reportData = $this->getStationFullReport($stationName);
                // **تحسين:** اطلب منه عرض التقرير بطريقة ودودة
                $promptForGemini = "أنت مساعد خبير في أنظمة المياه. طلب المستخدم تقريراً عن محطة. هذا هو التقرير الذي استخرجته من قاعدة البيانات:\n\n{$reportData}\n\nالآن، قم بعرض هذا التقرير للمستخدم بطريقة احترافية وواضحة.";
                $isHandledLocally = true;

            // الحالة 2: عدد آبار في محطة
            } elseif (preg_match('/(كم عدد|ما هو عدد) الآبار في محطة (.*)/u', $userMessage, $matches)) {
                $stationName = trim($matches[2]);
                $station = Station::withCount('wells')
                    ->whereRaw('LOWER(station_name) LIKE ?', ['%' . strtolower($stationName) . '%'])
                    ->first();

                if ($station) {
                    $count = $station->wells_count;
                    // **تحسين:** أعطه المعلومة واطلب منه صياغة الإجابة
                    $promptForGemini = "أنت مساعد خبير. أجب على سؤال المستخدم '{$userMessage}' بناءً على هذه المعلومة فقط: عدد الآبار في محطة {$station->station_name} هو {$count}. قدم الإجابة بشكل مباشر وودود.";
                } else {
                    $botResponseText = "عذراً، لم أتمكن من العثور على محطة بالاسم '{$stationName}'.";
                }
                $isHandledLocally = true;

            // الحالة 3: عدد الآبار بحالة معينة
            } elseif (preg_match('/(كم عدد|ما هو عدد) الآبار (.*)/u', $userMessage, $matches)) {
                $status = trim($matches[2]);
                if (str_contains($status, 'متوقفة') || str_contains($status, 'تعمل') || str_contains($status, 'يعمل')) {
                    $count = $this->countWellsByStatus($status);
                     // **تحسين:** نفس المبدأ
                    $promptForGemini = "أنت مساعد خبير. أجب على سؤال المستخدم '{$userMessage}' بناءً على هذه المعلومة فقط: العدد الإجمالي للآبار التي حالتها '{$status}' هو {$count}.";
                    $isHandledLocally = true;
                }

            // الحالة 4: قائمة المحطات بمحافظة
            } elseif (preg_match('/(ما هي|قائمة|اعرض) (محطات|المحطات) في (.*)/u', $userMessage, $matches)) {
                $governorateName = trim($matches[3]);
                $stations = $this->listStationsInGovernorate($governorateName);
                if (empty($stations)) {
                    $botResponseText = "عذراً، لا توجد محطات مسجلة في قاعدة البيانات لمحافظة {$governorateName}.";
                } else {
                    $stationList = implode('، ', $stations);
                     // **تحسين:** نفس المبدأ
                    $promptForGemini = "أنت مساعد خبير. أجب على سؤال المستخدم '{$userMessage}' بتقديم هذه القائمة: {$stationList}.";
                }
                $isHandledLocally = true;
            }

            // --- منطقة إرسال الـ Prompt إلى Gemini أو التعامل مع المحادثة العامة ---
            
            // إذا تم التعامل مع الطلب محلياً وهناك prompt جاهز
            if ($isHandledLocally && !empty($promptForGemini)) {
                $response = Gemini::generativeModel('gemini-1.5-flash-latest')->generateContent($promptForGemini);
                $botResponseText = $response->text();
            
            // إذا تم التعامل معه محلياً ولكن تم تحديد الرد مباشرة (مثل رسائل الخطأ)
            } elseif ($isHandledLocally && !empty($botResponseText)) {
                // لا تفعل شيئاً، سيتم استخدام $botResponseText المحدد مسبقاً
            
            // إذا لم يتم التعامل معه محلياً (محادثة عامة)
            } else {
                // --- الكود الجديد والمُصحح هنا ---
                // بناء شخصية وسياق للمحادثة بالكامل باللغة العربية
                $systemInstruction = "أنت مساعد ذكي ومتخصص في أنظمة المياه، اسمك 'خليل'. يجب أن تكون كل إجاباتك باللغة العربية فقط. لا تقدم ترجمات أو شروحات بالإنجليزية.";
                
                $chatHistory = collect($this->conversationHistory)
                    ->map(fn($msg) => ($msg['sender'] === 'bot' ? 'خليل: ' : 'المستخدم: ') . $msg['text'])
                    ->implode("\n");

                $generalPrompt = $systemInstruction . "\n\n--- سجل المحادثة السابق ---\n" . $chatHistory . "\n\n--- الرسالة الجديدة ---\nالمستخدم: " . $userMessage;
                
                $response = Gemini::generativeModel('gemini-1.5-flash-latest')->generateContent($generalPrompt);
                $botResponseText = $response->text();
            }
            $this->conversationHistory[] = ['sender' => 'bot', 'text' => $botResponseText];

        } catch (Exception $e) {
            $this->conversationHistory[] = ['sender' => 'bot', 'text' => 'عذراً، حدث خطأ: ' . $e->getMessage()];
        } finally {
            $this->dispatch('message-sent-and-received');
        }
    }

    public function render()
    {
        return view('livewire.chat-bot', [
            'messages' => $this->conversationHistory
        ])->layout('layouts.app');
    }
}
