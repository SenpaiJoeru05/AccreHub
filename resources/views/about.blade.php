<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>About | AccreHub</title>

  {{-- Your dark‑mode–only stylesheet --}}
  <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ time() }}" />

  {{-- Layout tweaks for About section --}}
  <style>
    .about-section {
      padding: 1rem 0 3rem;
      margin-top: -1rem;
    }
    .about-flex {
      display: flex;
      flex-wrap: wrap;
      gap: 2rem;
      align-items: center;
    }
    .about-content {
      flex: 1;
      min-width: 280px;
    }
    .about-content h2 {
      font-size: 2.5rem;
      margin-bottom: 1rem;
      font-weight: 800;
    }
    .about-content p {
      color: var(--text-secondary);
      margin-bottom: 2rem;
      line-height: 1.6;
    }
    .about-content .download-btn {
      padding: 0.75rem 1.75rem;
      font-size: 1.1rem;
    }
    .about-image {
      flex: 1;
      min-width: 280px;
      text-align: center;
    }
    .about-image img {
      max-width: 100%;
      height: auto;
      /* border-radius: var(--radius);
      box-shadow: var(--shadow-light); */
    }
  </style>
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

    <!-- About Section -->
    <section class="about-section">
      <div class="container">
        <div class="about-flex">

          <!-- Text column -->
          <div class="about-content">
            <h2>About AccreHub</h2>
            <p>
              AccreHub is your one‑stop portal for managing and searching accreditation 
              files across our institution. We believe transparency and accessibility 
              drive excellence—so we’ve built a platform that makes finding the exact 
              document you need as seamless as possible.
            </p>
            <a href="#" class="download-btn">Learn More</a>
          </div>

          <!-- Illustration column -->
          <div class="about-image">
            <img src="{{ asset('images/illustration.png') }}" alt="Team collaborating illustration">
          </div>
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
</body>
</html>
