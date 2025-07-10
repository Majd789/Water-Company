<div>
    <!-- DIRECT CHAT -->
    <div class="card direct-chat direct-chat-primary shadow-lg"
        style="height: 85vh; display: flex; flex-direction: column; border-radius: 15px;">

        <div class="card-header bg-gradient-primary" style="border-top-left-radius: 15px; border-top-right-radius: 15px;">
            <h3 class="card-title text-white">
                <i class="fas fa-robot mr-2"></i> KHalil AI
            </h3>
        </div>
        <!-- /.card-header -->

        <div class="card-body" style="flex-grow: 1; display: flex; flex-direction: column; padding: 0;">
            <!-- Conversations are loaded here -->
            <div id="chat-box" class="direct-chat-messages p-3" style="flex-grow: 1; overflow-y: auto;">

                {{-- حلقة Blade لعرض الرسائل --}}
                @forelse ($messages as $message)
                    @if ($message['sender'] === 'user')
                        <!-- Message to the right (User) -->
                        <div class="direct-chat-msg right">
                            <div class="direct-chat-infos clearfix">
                                <span class="direct-chat-name float-right">أنت</span>
                                <span class="direct-chat-timestamp float-left">{{ now()->format('h:i A') }}</span>
                            </div>
                            <img class="direct-chat-img"
                                src="https://ui-avatars.com/api/?name=Me&background=0d6efd&color=fff&bold=true"
                                alt="user image">
                            <div class="direct-chat-text shadow-sm"
                                style="border-radius: 18px; background-color: #007bff; border-color: #007bff;">
                                {{ $message['text'] }}
                            </div>
                        </div>
                        <!-- /.direct-chat-msg -->
                    @else
                        <!-- Message. Default to the left (Bot) -->
                        <div class="direct-chat-msg">
                            <div class="direct-chat-infos clearfix">
                                <span class="direct-chat-name float-left"> Khalil AI</span>
                                <span class="direct-chat-timestamp float-right">{{ now()->format('h:i A') }}</span>
                            </div>
                            <img class="direct-chat-img"
                                src="https://ui-avatars.com/api/?name=AI&background=6c757d&color=fff&bold=true"
                                alt="bot image">
                            <div class="direct-chat-text bg-light text-dark border-0 shadow-sm"
                                style="border-radius: 18px;">
                                {!! \Illuminate\Mail\Markdown::parse($message['text']) !!}
                            </div>
                        </div>
                        <!-- /.direct-chat-msg -->
                    @endif
                @empty
                    <div class="text-center text-muted" style="margin-top: 30vh;">
                        <i class="fas fa-comments fa-3x mb-3"></i>
                        <h4>ابدأ محادثتك الآن...</h4>
                    </div>
                @endforelse

            </div>
            <!--/.direct-chat-messages-->
        </div>
        <!-- /.card-body -->

        <div class="card-footer" style="border-bottom-left-radius: 15px; border-bottom-right-radius: 15px;">
            <form wire:submit.prevent="send">
                <div class="input-group">
                    <input type="text" wire:model="input" placeholder="اكتب رسالتك هنا..."
                        class="form-control form-control-lg" style="border-radius: 30px 0 0 30px;" autocomplete="off"
                        autofocus>
                    <span class="input-group-append">
                        <button type="submit" class="btn btn-primary btn-lg" style="border-radius: 0 30px 30px 0;">
                            <span wire:loading.remove wire:target="send">
                                <i class="fas fa-paper-plane"></i>
                            </span>
                            <span wire:loading wire:target="send">
                                <i class="fas fa-spinner fa-spin"></i>
                            </span>
                        </button>
                    </span>
                </div>
            </form>
        </div>
        <!-- /.card-footer-->
    </div>
    <!--/.direct-chat -->
</div>

{{-- كود JavaScript (نفس الكود السابق لأنه يعمل بشكل جيد) --}}
@script
    <script>
        document.addEventListener('livewire:initialized', () => {
            const chatBox = document.getElementById('chat-box');

            const scrollToBottom = () => {
                chatBox.scrollTop = chatBox.scrollHeight;
            }

            scrollToBottom();

            // تم تغيير اسم الحدث ليكون أكثر وضوحاً
            $wire.on('message-sent-and-received', (event) => {
                setTimeout(() => {
                    scrollToBottom();
                }, 10);
            });
        });
    </script>
@endscript
