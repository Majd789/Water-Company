<?php

use App\Livewire\Forms\LoginForm;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirect(RouteServiceProvider::HOME, navigate: false); // إعادة توجيه مع تحديث كامل
    }
}; ?>

@php
   $img3 = asset('img/3.jpg');
@endphp
<div class="card-body">
    
    <div class="login-card" >
        <div class="logo">
            <img style="width: 40% ;margin-top:10px ;margin-bottom: 30px" src="{{$img3}}">
        </div>
     
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
  
    <form wire:submit="login" class="login-form">
        <!-- Email Address -->
        
        <x-input-label style="color: black; font-weight: bold; font-size: large" for="email" :value="__('اسم المستخدم')" />
            <x-text-input wire:model="form.email" id="email" class="block mt-1 w-full" type="email" name="email" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
      

        <!-- Password -->
        
            <x-input-label style="color: black; font-weight: bold; font-size: large" for="password" :value="__('كلمة المرور')" />

            <x-text-input wire:model="form.password" id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
      

        <!-- Remember Me -->
        
            <label style="color: black" for="remember" class="inline-flex items-center">
                <input wire:model="form.remember" id="remember" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('تذكرني') }}</span>
            </label>
      

       
           

            <x-primary-button class="ms-3">
                {{ __('تسجيل الدخول ') }}
                
            </x-primary-button>
       
    </form>
</div>
