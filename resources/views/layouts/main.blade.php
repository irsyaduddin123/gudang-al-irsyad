<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Gudang Logistik Non-Medis</title>

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('templates/plugins/fontawesome-free/css/all.min.css') }}">

  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

  <!-- AdminLTE -->
  <link rel="stylesheet" href="{{ asset('templates/dist/css/adminlte.min.css') }}">

  <!-- Bootstrap 5 CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

  <!-- DataTables Bootstrap 5 CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  
  <!-- Navbar -->
  @include('layouts.component.navbar')

  <!-- Sidebar -->
  @include('layouts.component.sidebar')

  <!-- Content Wrapper -->
  <div class="content-wrapper">
    
    <!-- Header -->
    <section class="content-header">
      <div class="container-fluid">
        @yield('header')
      </div>
    </section>

    <!-- Main Content -->
    <section class="content">
      <div class="container-fluid">
        @yield('content')
      </div>
    </section>

    <!-- Back to Top -->
    <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
      <i class="fas fa-chevron-up"></i>
    </a>
  </div>

  <!-- Footer -->
  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 1.0
    </div>
    <strong>&copy; 2025 <a href="https://github.com/irsyaduddin123">AJI</a>.</strong> All rights reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Optional content -->
  </aside>

</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- AdminLTE -->
<script src="{{ asset('templates/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('templates/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('templates/dist/js/adminlte.min.js') }}"></script>
<script src="{{ asset('templates/dist/js/demo.js') }}"></script>

<!-- DataTables Bootstrap 5 JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<!-- Script Tambahan dari View -->
@yield('scripts')
</body>
</html>
