<link href="{{ asset('css/createoffice.css') }}" rel="stylesheet">
@extends('layouts.app')
@section('content')

<div class="recent-orders" style="text-align: center">
    <div class="login-card">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">    
        </h2>
                    <livewire:profile.update-profile-information-form  />
                    
                    <livewire:profile.update-password-form />
        
                    @if(auth()->check() && auth()->user()->role_id == 'admin') 
                    <livewire:profile.delete-user-form />   
                    @endif   
    </div>
</div>
@endsection