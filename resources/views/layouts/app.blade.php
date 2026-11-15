<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'AI Web Builder')</title>
  <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  @stack('styles')
</head>

<body>
  <header class="top-nav">
    <div class="nav-container">
      <a href="{{ route('home') }}" class="">AI Web Builder</a>
      <nav class="nav-links">
        <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
        <a href="{{ route('projects') }}" class="{{ request()->routeIs('projects') ? 'active' : '' }}">Projects</a>
        <a href="{{ route('help') }}" class="{{ request()->routeIs('help') ? 'active' : '' }}">Help</a>
        <a href="{{ route('login') }}" class="{{ request()->routeIs('login') ? 'active' : '' }}">Login</a>
      </nav>
    </div>
  </header>

  <main class="main-container">
    @yield('content')
  </main>

  @stack('scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
</body>
</html>
