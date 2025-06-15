<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>AccreHub | Home</title>
  <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ time() }}" />
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

    <!-- Hero / Search Section -->
    <section class="hero">
      <div class="container hero-inner">
        <h2>Welcome to AccreHub</h2>
        <p>Your one-stop solution for accreditation documents.</p>
        <div class="filters" style="display: flex; gap: 1rem; margin-bottom: 1rem;">
          <select id="area-filter" class="filter-select">
            <option value="">All Areas</option>
          </select>
          <select id="parameter-filter" class="filter-select" disabled>
            <option value="">All Parameters</option>
          </select>
          <select id="year-filter" class="filter-select">
            <option value="">All Years</option>
          </select>
        </div>
        <input
          type="text"
          class="search-bar"
          placeholder="Search for Accreditation Files..."
        >
      </div>
    </section>

    <!-- Search Results -->
    <section class="results container">
      <!-- Populated dynamically via JS -->
    </section>

    <!-- Footer -->
    <footer class="footer">
      <div class="container">
        © 2025 AccreHub. All rights reserved.
      </div>
    </footer>
  </div>

  <!-- PDF.js -->
  <script src="{{ asset('js/pdf.min.js') }}"></script>
  <script>
    window.pdfjsLib.GlobalWorkerOptions.workerSrc = '{{ asset('js/pdf.worker.min.js') }}';

    // Debounce helper
    function debounce(fn, delay) {
      let t;
      return (...args) => {
        clearTimeout(t);
        t = setTimeout(() => fn.apply(this, args), delay);
      };
    }

    // Render PDF preview (with retries & timeout)
    function renderPdfPreview(id, path, retries = 3) {
      const canvas = document.getElementById(`pdf-preview-${id}`);
      const overlay = document.getElementById(`loading-${id}`);
      if (!canvas || !overlay) return;

      const ctx = canvas.getContext('2d');
      ctx.clearRect(0, 0, canvas.width, canvas.height);

      const url = '{{ asset('storage') }}/' + path.split('/').map(encodeURIComponent).join('/');
      overlay.style.display = 'flex';

      const timeout = setTimeout(() => {
        overlay.innerHTML = '<span class="text-red-500 text-xs">Timeout</span>';
      }, 10000);

      pdfjsLib.getDocument(url).promise
      .then(pdf => pdf.getPage(1))
      .then(page => {
        clearTimeout(timeout);
        const vp = page.getViewport({ scale: 1.8 }); // Zoom in more
        canvas.width = vp.width;
        canvas.height = vp.height;
        return page.render({ canvasContext: ctx, viewport: vp }).promise;
      })
      .then(() => overlay.style.display = 'none')
      .catch(err => {
        if (retries > 0) {
          setTimeout(() => renderPdfPreview(id, path, retries - 1), 1000);
        } else {
          overlay.innerHTML = '<span class="text-red-500 text-xs">Error</span>';
        }
      });
    }

    const debouncedRender = debounce(renderPdfPreview, 200);

    // --- Dynamic Filter Loading and Search ---
    document.addEventListener('DOMContentLoaded', () => {
      const searchInput = document.querySelector('.search-bar');
      const results = document.querySelector('.results');
      const hero = document.querySelector('.hero');
      const areaFilter = document.getElementById('area-filter');
      const parameterFilter = document.getElementById('parameter-filter');
      const yearFilter = document.getElementById('year-filter');

      // Load Areas and Years on page load
      fetch('/media/filters')
        .then(res => res.json())
        .then(data => {
          // Populate Areas
          data.areas.forEach(area => {
            const opt = document.createElement('option');
            opt.value = area.id;
            opt.textContent = area.name;
            areaFilter.appendChild(opt);
          });
          // Populate Years
          data.years.forEach(year => {
            const opt = document.createElement('option');
            opt.value = year;
            opt.textContent = year;
            yearFilter.appendChild(opt);
          });
        });

      // Load Parameters when Area changes
      areaFilter.addEventListener('change', () => {
        parameterFilter.innerHTML = '<option value="">All Parameters</option>';
        parameterFilter.disabled = true;
        if (areaFilter.value) {
          fetch(`/media/parameters?area_id=${areaFilter.value}`)
            .then(res => res.json())
            .then(data => {
              data.parameters.forEach(param => {
                const opt = document.createElement('option');
                opt.value = param.id;
                opt.textContent = param.name;
                parameterFilter.appendChild(opt);
              });
              parameterFilter.disabled = false;
            });
        }
        // Hide results if search bar is empty, otherwise fetch
        if (searchInput.value.trim() !== '') {
          fetchMedia();
        } else {
          results.classList.remove('visible');
          results.innerHTML = '';
        }
      });

      parameterFilter.addEventListener('change', () => {
        if (searchInput.value.trim() !== '') {
          fetchMedia();
        } else {
          results.classList.remove('visible');
          results.innerHTML = '';
        }
      });

      yearFilter.addEventListener('change', () => {
        if (searchInput.value.trim() !== '') {
          fetchMedia();
        } else {
          results.classList.remove('visible');
          results.innerHTML = '';
        }
      });

      searchInput.addEventListener('input', debounce(() => {
        if (searchInput.value.trim() !== '') {
          fetchMedia();
        } else {
          results.classList.remove('visible');
          results.innerHTML = '';
        }
      }, 300));

      function fetchMedia() {
        const query = searchInput.value.trim();
        if (query === '') {
          results.classList.remove('visible');
          results.innerHTML = '';
          return;
        }
        const area = areaFilter.value;
        const parameter = parameterFilter.value;
        const year = yearFilter.value;

        results.innerHTML = '<div class="loading-overlay">Loading…</div>';
        const params = new URLSearchParams({
          query,
          area,
          parameter,
          year
        });
        fetch(`/media/search?${params.toString()}`)
          .then(res => {
            if (!res.ok) {
              throw new Error(`HTTP error! status: ${res.status}`);
            }
            return res.json();
          })
          .then(data => {
            if (data.error) {
              throw new Error(data.error);
            }

            hero.classList.add('active');
            results.classList.add('visible');

            if (!data.records || data.records.length === 0) {
              results.innerHTML = '<p class="text-secondary">No documents found.</p>';
              return;
            }

            results.innerHTML = data.records.map(r => `
                <div class="result-card">
                    ${r.is_pdf
                        ? `<div class="pdf-preview">
                            <canvas id="pdf-preview-${r.id}"></canvas>
                            <div id="loading-${r.id}" class="loading-overlay"></div>
                        </div>`
                        : r.is_image
                        ? `<div class="image-preview">
                            <img src="{{ asset('storage') }}/${r.relative_path}" alt="${r.name}">
                            <div id="loading-${r.id}" class="loading-overlay"></div>
                        </div>`
                        : `<div class="image-preview"><span class="text-secondary">No Preview</span></div>`
                    }
                    <h3>${r.name}</h3>
                    <p>Year: ${r.year || ''}</p>
                    <p>Parameter: ${r.parameter_name || ''}</p>
                    <a href="{{ asset('storage') }}/${r.relative_path}" target="_blank" class="download-btn">View</a>
                </div>
            `).join('');

            // Render PDF previews
            data.records.forEach((r, i) => {
              if (r.is_pdf) {
                setTimeout(() => debouncedRender(r.id, r.relative_path), i * 300);
              }
            });
          })
          .catch(error => {
            console.error('Error fetching media:', error);
            results.innerHTML = `
                <div class="error-message">
                    <p>Error loading results: ${error.message}</p>
                    <button onclick="fetchMedia()" class="retry-btn">Retry</button>
                </div>
            `;
          });
      }
    });
  </script>
</body>
</html>