<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ env('APP_NAME') }} @yield('pagetitle') E-Commerce Project</title>

  <!-- DaisyUI ve Tailwind CSS -->
  <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.13/dist/full.min.css" rel="stylesheet" type="text/css" />
  <script src="https://cdn.tailwindcss.com"></script>
  
  <script>
    function changeTheme(theme) {
      document.documentElement.setAttribute('data-theme', theme);
    }
  </script>
</head>
<body class="bg-base-100 flex flex-col min-h-screen">

  <!-- Navbar -->
  <header class="w-full">
    @include('frontend.navbar')
  </header>

  <!-- Ana İçerik -->
  <main class="flex-grow container mx-auto p-4 md:p-8">
    @yield('content')
  </main>

  <!-- Footer -->
  <footer class="w-full mt-auto bg-base-300 p-4 md:p-8">
    @include('frontend.footer')
  </footer>

</body>
</html>
