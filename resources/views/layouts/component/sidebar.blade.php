@php
use App\Models\Permintaan;
use App\Models\Pengadaan;
use App\Models\BarangMasuk;

$user = auth()->user();
$role = $user->role ?? 'guest';

$jumlahMenunggu = $jumlahButuhValidasi = $jumlahPengadaanButuhValidasi = $jumlahBelumDiterima = 0;

if ($role === 'staff') {
    $jumlahMenunggu = Permintaan::where('status', 'menunggu')->count();
    $jumlahBelumDiterima = BarangMasuk::whereNull('tanggal_diterima')->count();
} elseif ($role === 'manager') {
    $jumlahButuhValidasi = Permintaan::where('status', 'butuh_validasi_manager')->count();
    $jumlahPengadaanButuhValidasi = Pengadaan::where('status', 'menunggu')->count();
}

$allMenus = [
    // Utama
    (object)['group' => 'Utama', 'group_icon' => 'fa fa-tachometer-alt', 'title' => 'Dashboard', 'path' => '/', 'icon' => 'fa fa-home'],

    // Pengelolaan
    (object)['group' => 'Pengelolaan', 'group_icon' => 'fa fa-cogs', 'title' => 'Barang', 'path' => '/barang', 'icon' => 'fa fa-boxes'],
    (object)['group' => 'Pengelolaan', 'group_icon' => 'fa fa-cogs', 'title' => 'Pengguna', 'path' => 'pengguna', 'icon' => 'fa fa-user'],
    (object)['group' => 'Pengelolaan', 'group_icon' => 'fa fa-cogs', 'title' => 'Supplier', 'path' => 'supplier', 'icon' => 'fa fa-truck'],
    (object)['group' => 'Pengelolaan', 'group_icon' => 'fa fa-cogs', 'title' => 'Safety Stok', 'path' => 'safety', 'icon' => 'fa fa-shield-alt'],

    // Transaksi
    (object)['group' => 'Transaksi', 'group_icon' => 'fa fa-exchange-alt', 'title' => 'Permintaan', 'path' => 'permintaan', 'icon' => 'fa fa-shopping-cart'],
    (object)['group' => 'Transaksi', 'group_icon' => 'fa fa-exchange-alt', 'title' => 'Barang Keluar', 'path' => 'barang-keluar', 'icon' => 'fa fa-arrow-right'],
    (object)['group' => 'Transaksi', 'group_icon' => 'fa fa-exchange-alt', 'title' => 'Barang Masuk', 'path' => 'barang-masuk', 'icon' => 'fa fa-arrow-left'],
    (object)['group' => 'Transaksi', 'group_icon' => 'fa fa-exchange-alt', 'title' => 'Pengadaan', 'path' => 'pengadaan', 'icon' => 'fa fa-truck-loading'],

    // Perhitungan
    (object)['group' => 'Perhitungan', 'group_icon' => 'fa fa-calculator', 'title' => 'Hasil ROP dan EOQ', 'path' => 'hasil', 'icon' => 'fa fa-chart-line'],
];

$menus = collect($allMenus);
if (!in_array($role, ['staff', 'manager', 'admin'])) {
    $menus = $role === 'permintaan'
        ? $menus->filter(fn($m) => $m->title === 'Permintaan')
        : collect([]);
}

$groupedMenus = $menus->groupBy('group');
@endphp

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <div class="sidebar">
        <!-- User Info -->
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

        <!-- Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" role="menu" data-widget="treeview" data-accordion="false">
                @foreach ($groupedMenus as $group => $items)
                    @php
                        $icon = $items->first()->group_icon ?? 'fa fa-folder';
                        $isOpen = $items->contains(fn($m) => request()->is(trim($m->path, '/')));
                    @endphp

                    <li class="nav-item has-treeview {{ $isOpen ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ $isOpen ? 'active' : '' }}">
                            <i class="nav-icon {{ $icon }}"></i>
                            <p>{{ strtoupper($group) }}<i class="right fas fa-angle-left"></i></p>
                        </a>

                        <ul class="nav nav-treeview">
                            @foreach ($items as $menu)
                                @php
                                    $isActive = request()->is(trim($menu->path, '/'));
                                    $badge = null;
                                    $badgeColor = 'badge-warning';

                                    if ($menu->title === 'Permintaan') {
                                        if ($role === 'staff' && $jumlahMenunggu > 0) {
                                            $badge = $jumlahMenunggu;
                                        } elseif ($role === 'manager' && $jumlahButuhValidasi > 0) {
                                            $badge = $jumlahButuhValidasi;
                                            $badgeColor = 'badge-danger';
                                        }
                                    }

                                    if ($menu->title === 'Barang Masuk' && $role === 'staff' && $jumlahBelumDiterima > 0) {
                                        $badge = $jumlahBelumDiterima;
                                        $badgeColor = 'badge-info';
                                    }

                                    if ($menu->title === 'Pengadaan' && $role === 'manager' && $jumlahPengadaanButuhValidasi > 0) {
                                        $badge = $jumlahPengadaanButuhValidasi;
                                        $badgeColor = 'badge-danger';
                                    }
                                @endphp

                                <li class="nav-item">
                                    <a href="{{ url($menu->path) }}" class="nav-link {{ $isActive ? 'active' : '' }}">
                                        <i class="nav-icon {{ $menu->icon }}"></i>
                                        <p>
                                            {{ $menu->title }}
                                            @if ($badge)
                                                <span class="right badge {{ $badgeColor }}">
                                                    <i class="fa fa-bell"></i> {{ $badge }}
                                                </span>
                                            @endif
                                        </p>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endforeach

                <!-- Logout -->
                <li class="nav-item mt-3">
                    <button class="nav-link btn btn-link text-left text-white" style="width: 100%;" data-bs-toggle="modal" data-bs-target="#logoutModal">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </button>
                </li>
            </ul>
        </nav>
    </div>
</aside>

<!-- Logout Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Konfirmasi Logout</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        Apakah Anda yakin ingin logout?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger">Logout</button>
        </form>
      </div>
    </div>
  </div>
</div>
