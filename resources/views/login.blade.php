<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login | AccreHub</title>
  <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
   <!-- Add Font Awesome for eye icon -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
   <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
  <div class="page-wrapper">
    <!-- Header -->
    <header class="header">
      <div class="container header-flex">
        <a href="{{ route('home') }}">
          <img src="{{ asset('assets/accrehub1.png') }}" alt="AccreHub Logo" class="logo">
        </a>
        <nav>
          <a href="{{ route('home') }}">Home</a>
          <a href="{{ route('about') }}">About</a>
          <a href="{{ route('login') }}">Login</a>
        </nav>
      </div>
    </header>

    <!-- Login Section -->
    <section class="hero">
      <div class="container">
        <div class="login-container">
          <div class="login-header">
          <img src="{{ asset('assets/accrehub1.png') }}" alt="AccreHub Logo" class="logo">
          </div>
          
          @if ($errors->any())
            <div class="error-message">
              @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
              @endforeach
            </div>
          @endif

          @if (session('status'))
            <div class="error-message">
              <p>{{ session('status') }}</p>
            </div>
          @endif
          
          <form id="loginForm" action="{{ route('login') }}" method="POST">
            @csrf
            <div class="form-group">
              <label for="email">Email</label>
              <input type="email" id="email" name="email" class="form-control" required placeholder="Enter your email">
            </div>
            
            <div class="form-group">
              <label for="password">Password</label>
              <div class="input-wrapper">
                <input type="password" id="password" name="password" class="form-control" required placeholder="Enter your password">
                <button type="button" class="password-toggle" id="togglePassword">
                  <i class="far fa-eye"></i>
                </button>
              </div>
            </div>
            
            <div class="remember-me">
              <input type="checkbox" id="remember" name="remember">
              <label for="remember">Remember me</label>
            </div>
            
            <button type="submit" class="login-btn">Sign In</button>
            
          </form>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
      <div class="container">
        © 2025 AccreHub. All rights reserved.
      </div>
    </footer>
  </div>

  <script>
    // Toggle password visibility
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');
    
    togglePassword.addEventListener('click', function() {
      const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
      password.setAttribute('type', type);
      this.innerHTML = type === 'password' ? '<i class="far fa-eye"></i>' : '<i class="far fa-eye-slash"></i>';
    });
  </script>
</body>
</html>