<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>@yield('title', 'Admin Panel')</title>

  <!-- Tailwind -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Bootstrap CSS -->
  <link 
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" 
    rel="stylesheet">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

  <style>
    body { 
      font-family: 'Inter', sans-serif; 
    }
  </style>
</head>

<body class="bg-gray-100">

  {{-- NAVBAR / SIDEBAR --}}
  @yield('content')

  <!-- Bootstrap JS -->
  <script 
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js">
  </script>

  @stack('scripts')

</body>
</html>
