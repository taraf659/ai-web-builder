@extends('layouts.app')

@section('title', 'Register - AI Web Builder')

@section('content')
<div class="modern-card">
  <div class="form-container">
    <h1 class="form-title">Join Us Today</h1>
    <p class="form-subtitle">Create your account and start building amazing websites with AI</p>

    <form id="register-form">
      <div class="input-group">
        <label for="username">Username</label>
        <input type="text" id="username" class="input-field" placeholder="Choose a username" required>
        <i class='bx bx-user input-icon'></i>
      </div>

      <div class="input-group">
        <label for="email">Email Address</label>
        <input type="email" id="email" class="input-field" placeholder="Enter your email" required>
        <i class='bx bx-envelope input-icon'></i>
      </div>

      <div class="input-group">
        <label for="password">Password</label>
        <input type="password" id="password" class="input-field" placeholder="Create a password" required>
        <i class='bx bx-lock-alt input-icon'></i>
      </div>

      <div class="input-group">
        <label for="confirm-password">Confirm Password</label>
        <input type="password" id="confirm-password" class="input-field" placeholder="Confirm your password" required>
        <i class='bx bx-lock input-icon'></i>
      </div>

      <button type="submit" class="btn-primary">Create Account</button>
    </form>

    <div class="link-text">
      <p>Already have an account? <a href="{{ route('login') }}">Sign in here</a></p>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
const registerForm = document.getElementById('register-form');

registerForm.addEventListener('submit', function(e) {
  e.preventDefault();

  const username = document.getElementById('username').value.trim();
  const email = document.getElementById('email').value.trim();
  const password = document.getElementById('password').value.trim();
  const confirmPassword = document.getElementById('confirm-password').value.trim();

  if (!username || !email || !password || !confirmPassword) {
    alert("Please fill in all fields!");
    return;
  }

  if (password !== confirmPassword) {
    alert("Passwords do not match!");
    return;
  }

  const user = { username, email, password };
  localStorage.setItem('user', JSON.stringify(user));

  alert("Account created successfully! Welcome to AI Web Builder!");
  window.location.href = "{{ route('home') }}";
});
</script>
@endpush