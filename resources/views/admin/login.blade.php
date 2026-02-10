<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Elektron Login</title>

<!-- Tailwind -->
<script src="https://cdn.tailwindcss.com"></script>

<style>
/* ===== LOADING ===== */
.spinner {
  width: 44px;
  height: 44px;
  border: 4px solid rgba(255,255,255,.3);
  border-top: 4px solid white;
  border-radius: 50%;
  animation: spin .9s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg) } }
</style>
</head>

<body class="bg-gray-100 min-h-screen transition-colors duration-300">

<!-- ===== LOADING OVERLAY ===== -->
<div id="loading"
class="fixed inset-0 bg-black/60 backdrop-blur-md hidden z-50 grid place-items-center">
  <div class="flex flex-col items-center gap-4 text-white">
    <div class="spinner"></div>
    <p id="loadingText" class="font-semibold">Memverifikasi akun...</p>
  </div>
</div>

<!-- ===== LOGIN CARD ===== -->
<div class="min-h-screen flex items-center justify-center px-4">
  <div class="bg-white w-full max-w-md rounded-2xl shadow-xl p-10">

    <h1 class="text-3xl font-bold text-purple-700 text-center">
      Admin Elektron
    </h1>
    <p class="text-center text-gray-500 mt-2 mb-8">
      Login untuk melanjutkan
    </p>

    <form id="loginForm" class="space-y-6">

      <input id="username" placeholder="Username"
      class="w-full px-4 py-3 rounded-xl border
      focus:ring-2 focus:ring-purple-500 outline-none">

      <input id="password" type="password" placeholder="Password"
      class="w-full px-4 py-3 rounded-xl border
      focus:ring-2 focus:ring-purple-500 outline-none">

      <button
      class="w-full bg-purple-700 hover:bg-purple-800
      text-white py-3 rounded-xl font-semibold transition">
        Login
      </button>

      <p id="message" class="text-center text-sm hidden"></p>
    </form>

    <p class="text-center mt-6 text-gray-400 text-sm">
      © 2026 Elektron Admin Panel
    </p>
  </div>
</div>

<script>
/* ===== LOGIN ===== */
loginForm.onsubmit = async e => {
  e.preventDefault();
  message.classList.add('hidden');

  loading.classList.remove('hidden');
  loadingText.textContent = 'Memverifikasi akun...';

  try {
    const res = await fetch('/api/admin/login', {
      method:'POST',
      headers:{
        'Content-Type':'application/json',
        'Accept':'application/json'
      },
      body:JSON.stringify({
        admin_username: username.value,
        admin_password: password.value
      })
    });

    const data = await res.json();
    if (!res.ok) throw data.message;

    loadingText.textContent = 'Login berhasil';
    localStorage.setItem('admin_token', data.token);

    setTimeout(() => {
      window.location.href = '/admin/dashboard';
    }, 1200);

  } catch (err) {
    loading.classList.add('hidden');
    message.textContent = err || 'Username atau password salah';
    message.className = 'text-center text-red-500';
  }
};
</script>

</body>
</html>
