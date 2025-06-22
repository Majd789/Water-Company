<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <link href="{{ asset('css/welcome.css') }}" rel="stylesheet">
       
       
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
        <title>Water Db</title>
        <!-- Fonts -->
        <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    </head>
    <body class="antialiased" >
        
                <nav class="navbar navbar-expand-lg navbar-light bg-light">
                    <div class="container-fluid">
                        <div class="d-flex align-items-center">
                            <a class="navbar-brand" href="#">
                                <img src="img\3.jpg" alt="Your Logo" width="50" height="50">
                            </a>
                            <div class="collapse navbar-collapse justify-content-end">
                                <ul class="navbar-nav">
                                    @if (Route::has('login'))
                                        @auth
                                            <li class="nav-item">
                                                <a class="nav-link" href="{{ url('/dashboard') }}">home</a>
                                            </li>
                                        @else
                                            <li class="nav-item">
                                                <a class="nav-link" href="{{ route('login') }}">Log in</a>
                                            </li>
                                            
                                            @if (Route::has('register'))
                                                <li class="nav-item">
                                                    <a class="nav-link" href="{{ route('register') }}">Register</a>
                                                </li>
                                            @endif
                                        @endauth
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </nav>       
            <div class="main" id="main">
                <div class="left">
                    <h5>Hey,i am Ghazi Khalil </h5>
                    <h3>This is project for <br><span>the public foundation For drinking</span></h3>
                    <p>
                        Supervised by engineers:
                        <ul>
                            <li>Abo khaled</li>
                            
                        </ul>
                    </p>
                    
                </div>
                <div class="right">
                    <img src="img/3.jpg" style="border-radius: 50%;">
                </div>
            </div>
                
            
        </div>
    </body>
</html>
