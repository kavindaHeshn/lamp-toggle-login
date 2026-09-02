<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Interactive Lamp Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <style>
    :root {
      --cord: #4a5568;
      --tongue: #f6e05e;
      --bg-color: #1a1a2e;
      --card-bg: rgba(255, 255, 255, 0.05);
      --card-border: rgba(255, 255, 255, 0.1);
      --text-color: #ffffff;
      --text-muted: #888888;
      --input-bg: rgba(255, 255, 255, 0.08);
      --input-border: rgba(255, 255, 255, 0.15);
      --btn-bg: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      --glow-color: rgba(253, 224, 71, 0.3);
    }

    [data-theme="light"] {
      --cord: #2d3748;
      --tongue: #d69e2e;
      --bg-color: #f0f4f8;
      --card-bg: #ffffff;
      --card-border: rgba(0, 0, 0, 0.08);
      --text-color: #1a202c;
      --text-muted: #718096;
      --input-bg: #f7fafc;
      --input-border: #e2e8f0;
      --btn-bg: linear-gradient(135deg, #4c51bf 0%, #6b46c1 100%);
      --glow-color: rgba(250, 204, 21, 0.4);
    }

    body {
      background-color: var(--bg-color);
      color: var(--text-color);
      transition: background-color 0.5s ease, color 0.5s ease;
      font-family: 'Inter', system-ui, -apple-system, sans-serif;
    }

    /* Exact CSS rules from user's code snippet */
    .lamp {
      display: block; /* Overriding none to show responsive lamp SVG */
      height: 40vmin;
      overflow: visible !important;
    }

    .cord {
      stroke: var(--cord);
      transition: stroke 0.3s ease;
    }

    .cord--rig {
      display: none;
    }

    .lamp__tongue {
      fill: var(--tongue);
      transition: fill 0.3s ease;
    }

    .login-btn:active {
      transform: translateY(0);
    }

    .form-footer {
      margin-top: 1.5rem;
      text-align: center;
    }

    .forgot-link {
      color: #888;
      font-size: 0.9rem;
      text-decoration: none;
      transition: all 0.3s ease;
    }

    .forgot-link:hover {
      color: #667eea;
      text-decoration: underline;
    }

    .lamp-glow {
      filter: drop-shadow(0 0 35px var(--glow-color));
      transition: filter 0.5s ease;
    }

    .pull-handle {
      cursor: pointer;
      transform-origin: top center;
      transition: transform 0.2s ease;
    }

    .pull-handle:hover {
      transform: translateY(5px);
    }

    .card-glass {
      background: var(--card-bg);
      border: 1px solid var(--card-border);
      backdrop-filter: blur(16px);
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
    }
  </style>
</head>
<body class="min-h-screen flex flex-col justify-between items-center relative overflow-x-hidden p-4">

  <header class="w-full flex justify-center pt-0 relative z-10">
    <div class="relative flex flex-col items-center">
      <!-- Lamp Fixture & Cable SVG -->
      <svg class="lamp lamp-glow" viewBox="0 0 200 300" width="200">
        <!-- Ceiling Fixture -->
        <rect x="85" y="0" width="30" height="10" rx="3" fill="#4A5568" />
        
        <!-- Hanging Cord -->
        <line x1="100" y1="10" x2="100" y2="120" class="cord" stroke-width="4" stroke-dasharray="none" />
        <line x1="100" y1="10" x2="100" y2="120" class="cord--rig" stroke-width="2" />

        <!-- Lamp Shade -->
        <path d="M 50 160 L 150 160 L 170 200 C 170 215, 30 215, 30 200 Z" fill="#2D3748" class="transition-all duration-300" />
        <path d="M 70 120 L 130 120 L 150 160 L 50 160 Z" fill="#4A5568" />

        <!-- Lamp Bulb / Tongue -->
        <ellipse cx="100" cy="205" rx="40" ry="15" class="lamp__tongue" id="lampBulb" />

        <!-- Interactive Pull Cord -->
        <g id="pullCord" class="pull-handle">
          <line x1="140" y1="160" x2="140" y2="240" stroke="var(--cord)" stroke-width="3" stroke-dasharray="4,4" />
          <circle cx="140" cy="245" r="8" fill="#F6E05E" stroke="#D69E2E" stroke-width="2" />
        </g>
      </svg>
      <div class="text-xs opacity-60 mt-1 select-none font-medium flex items-center gap-1">
        <i class="fa-solid fa-arrow-up text-[10px]"></i> Pull bulb cord to switch light/dark mode
      </div>
    </div>
  </header>

  <main class="w-full max-w-md my-auto relative z-10">
    <div class="card-glass rounded-2xl p-8 transition-all duration-500">
      <div class="text-center mb-8">
        <div class="w-16 h-16 mx-auto mb-3 rounded-full flex items-center justify-center bg-indigo-600/10 text-indigo-500 text-2xl shadow-inner">
          <i class="fa-solid fa-user-shield"></i>
        </div>
        <h1 class="text-2xl font-bold tracking-tight">Welcome Back</h1>
        <p class="text-sm text-gray-400 mt-1">Please enter your login details</p>
      </div>

      <form id="loginForm" class="space-y-5" onsubmit="event.preventDefault(); handleLogin();">
        <!-- Email Input -->
        <div>
          <label class="block text-sm font-medium mb-2 opacity-90" for="email">Email Address</label>
          <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
              <i class="fa-regular fa-envelope"></i>
            </span>
            <input type="email" id="email" required
              class="w-full pl-10 pr-4 py-3 rounded-xl border focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all text-sm"
              style="background-color: var(--input-bg); border-color: var(--input-border); color: var(--text-color);"
              placeholder="user@example.com">
          </div>
        </div>

        <!-- Password Input -->
        <div>
          <label class="block text-sm font-medium mb-2 opacity-90" for="password">Password</label>
          <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
              <i class="fa-solid fa-lock"></i>
            </span>
            <input type="password" id="password" required
              class="w-full pl-10 pr-4 py-3 rounded-xl border focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all text-sm"
              style="background-color: var(--input-bg); border-color: var(--input-border); color: var(--text-color);"
              placeholder="••••••••">
          </div>
        </div>

        <!-- Action Button -->
        <button type="submit" 
          class="login-btn w-full py-3.5 px-4 rounded-xl text-white font-semibold shadow-lg hover:shadow-indigo-500/25 transform transition-all duration-200 hover:-translate-y-0.5"
          style="background: var(--btn-bg);">
          Sign In
        </button>
      </form>

      <!-- Form Footer with custom class from prompt -->
      <div class="form-footer">
        <a href="#" class="forgot-link" onclick="handleForgot(event)">Forgot your password?</a>
      </div>
    </div>
  </main>

  <!-- Notification Toast Modal -->
  <div id="toast" class="fixed bottom-6 right-6 translate-y-20 opacity-0 transition-all duration-300 pointer-events-none bg-indigo-600 text-white px-5 py-3 rounded-xl shadow-xl flex items-center gap-3 z-50">
    <i class="fa-solid fa-circle-info text-lg"></i>
    <span id="toastMsg" class="text-sm font-medium">Notification</span>
  </div>

  <!-- Footer Info -->
  <footer class="text-xs text-center opacity-40 py-4">
    Interactive Lamp & Auth Form Interface &copy; 2026
  </footer>

  <script>
    // State management
    let isLit = true;
    const body = document.body;
    const pullCord = document.getElementById('pullCord');
    const toast = document.getElementById('toast');
    const toastMsg = document.getElementById('toastMsg');

    // Lamp pull trigger interactive toggle
    pullCord.addEventListener('click', () => {
      // Cord bounce animation effect
      pullCord.style.transform = 'translateY(15px)';
      setTimeout(() => {
        pullCord.style.transform = 'translateY(0)';
      }, 200);

      // Toggle Theme
      isLit = !isLit;
      if (isLit) {
        body.removeAttribute('data-theme');
        showToast('Switched to Dark Mode');
      } else {
        body.setAttribute('data-theme', 'light');
        showToast('Switched to Light Mode');
      }
    });

    // Custom Toast Notification function
    function showToast(message) {
      toastMsg.textContent = message;
      toast.classList.remove('translate-y-20', 'opacity-0', 'pointer-events-none');
      setTimeout(() => {
        toast.classList.add('translate-y-20', 'opacity-0', 'pointer-events-none');
      }, 3000);
    }

    // Login Form Handler
    function handleLogin() {
      const email = document.getElementById('email').value;
      showToast(`Welcome back, ${email}!`);
    }

    // Forgot Password Handler
    function handleForgot(e) {
      e.preventDefault();
      showToast('Password reset link sent to your email address!');
    }
  </script>
</body>
</html>