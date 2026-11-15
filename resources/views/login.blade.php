@extends('layouts.app')

@section('title', 'Login - AI Web Builder')

@section('content')
    <div class="modern-card">
      <div class="form-container">
        <h1 class="form-title">Welcome Back</h1>
        <p class="form-subtitle">Sign in to continue building amazing websites</p>

        <form id="login-form">
          <div class="input-group">
            <label for="username">Username</label>
            <input type="text" id="username" class="input-field" placeholder="Enter your username" required>
            <i class='bx bx-user input-icon'></i>
          </div>

          <div class="input-group">
            <label for="password">Password</label>
            <input type="password" id="password" class="input-field" placeholder="Enter your password" required>
            <i class='bx bx-lock-alt input-icon'></i>
          </div>

          <div class="input-group">
            <label>
              <input type="checkbox" style="margin-right: 8px;"> Remember me
            </label>
          </div>

          <button type="submit" class="btn-primary">Sign In</button>
        </form>

        <div class="link-text">
          <p>Don't have an account? <a href="{{ route('register') }}">Create one here</a></p>
        </div>
      </div>
    </div>
  </div>

  <script>
    const loginForm = document.getElementById('login-form');

    loginForm.addEventListener('submit', function(e) {
      e.preventDefault();

      const username = document.getElementById('username').value.trim();
      const password = document.getElementById('password').value.trim();

      if (!username || !password) {
        alert("Please enter both username and password!");
        return;
      }

      const storedUser = JSON.parse(localStorage.getItem('user'));

      if (!storedUser) {
        alert("No user found. Please register first!");
        return;
      }

      if (username === storedUser.username && password === storedUser.password) {
        alert("Welcome back! Login successful!");
        window.location.href = "{{ route('home') }}";
      } else {
        alert("Incorrect username or password!");
      }
    });
  </script>
@endsection

@push('scripts')
<script>
  // Additional scripts can go here if needed
</script>
@endpush