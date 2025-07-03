@php
    $user = auth()->user();
    $role = $user->role ?? 'guest';

    // Semua menu utama
    $allMenus = [
        (object)[
            'title' => 'Dashboard',
            'path' => '/',
            'icon' => 'fa fa-home'
        ],
        (object)[
            'title' => 'Barang',
            'path' => '/barang',
            'icon' => 'fa fa-warehouse'
        ],
        (object)[
            'title' => 'Permintaan',
            'path' => 'permintaan',
            'icon' => 'fa fa-shopping-cart'
        ],
        (object)[
            'title' => 'Pengguna',
            'path' => 'pengguna',
            'icon' => 'fa fa-users'
        ],
        (object)[
            'title' => 'Supplier',
            'path' => 'supplier',
            'icon' => 'fa fa-truck'
        ],
        (object)[
            'title' => 'Safety Stok',
            'path' => 'safety',
            'icon' => 'fa fa-shield-alt'
        ],
        (object)[
            'title' => 'Hasil ROP dan EOQ',
            'path' => 'hasil',
            'icon' => 'fa fa-calculator'
        ],
    ];

    // Filter berdasarkan role
    if (in_array($role, ['staff', 'manager', 'admin'])) {
        $menus = collect($allMenus);
    } elseif ($role === 'permintaan') {
        $menus = collect($allMenus)->filter(fn($menu) => $menu->title === 'Permintaan');
    } else {
        $menus = collect([]); // kosong kalau bukan role dikenal
    }
@endphp

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <!-- Brand Logo -->
    {{-- <a href="{{ route('dashboard') }}" class="brand-link">
        <img src="{{ asset('templates/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">
            @auth
                {{ Auth::user()->bagian ?? Auth::user()->role }}
            @endauth
        </span>
    </a> --}}

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('templates/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                @auth
                    <a href="#" class="d-block">{{ Auth::user()->nama }}</a>
                @endauth
            </div>
        </div>

      <!-- SidebarSearch Form -->
      {{-- <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div> --}}
      {{-- @dd(request()->path()) --}}

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          {{-- <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="../index.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Dashboard v1</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="../index2.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Dashboard v2</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="../index3.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Dashboard v3</p>
                </a>
              </li>
            </ul>
          </li> --}}
          @foreach ($menus as $menu)
          <li class="nav-item">
              <a href="{{$menu->path[0] !== '/' ? '/' . $menu->path : $menu->path }}" class="nav-link {{ request()->path() == $menu->path ? 'active' : '' }}">
                <i class="nav-icon {{$menu->icon}}"></i>
                <p>
                    {{$menu->title}}
                    {{-- <span class="right badge badge-danger">New</span> --}}
                </p>
            </a>
        </li>
        @endforeach
        <!-- Logout -->
                <li class="nav-item">
    <button class="nav-link btn btn-link text-left text-white"
            style="width: 100%;"
            data-bs-toggle="modal"
            data-bs-target="#logoutModal">
        <i class="nav-icon fas fa-sign-out-alt"></i>
        <p>Logout</p>
    </button>
</li>
    </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Logout Confirmation Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="logoutModalLabel">Konfirmasi Logout</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        Apakah Anda yakin ingin logout?
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>

        <!-- Form logout sebenarnya -->
        <form id="logoutForm" action="{{ route('logout') }}" method="POST">
          @csrf
          <button type="submit" class="btn btn-danger">Logout</button>
        </form>
      </div>
    </div>
  </div>
</div>
