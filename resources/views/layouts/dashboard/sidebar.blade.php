  <!-- Sidebar -->
  <aside class="sidebar">
      <!-- Sidebar Navigation -->
      <nav class="sidebar-nav">
          <ul class="nav-menu">

              <!-- Main Dashboard -->
              <li class="nav-item">
                  <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                      <span class="nav-icon"><i class="ph-light ph-squares-four"></i></span>
                      <span class="nav-text">Dashboard</span>
                      <span class="nav-badge nav-badge-soft">Main</span>
                  </a>
              </li>

              <li class="nav-item">
                  <a class="nav-link " href="apps-calendar.html">
                      <span class="nav-icon"><i class="ph-light ph-users-three"></i></span>
                      <span class="nav-text">Pegawai</span>
                  </a>
              </li>

              <!-- Dashboards Submenu -->
              {{-- <li class="nav-item has-submenu ">
          <a class="nav-link" href="#" aria-expanded="false">
            <span class="nav-icon"><i class="ph-light ph-gauge"></i></span>
            <span class="nav-text">Dashboards</span>
            <span class="nav-arrow"><i class="bi bi-chevron-right"></i></span>
          </a>
          <ul class="nav-submenu ">
            <li><a class="nav-link " href="dashboard-sales.html"><span class="nav-dot"></span>Sales</a></li>
            <li><a class="nav-link " href="dashboard-analytics.html"><span class="nav-dot"></span>Analytics</a></li>
            <li><a class="nav-link " href="dashboard-crm.html"><span class="nav-dot"></span>CRM</a></li>
            <li><a class="nav-link " href="dashboard-marketing.html"><span class="nav-dot"></span>Marketing</a></li>
            <li><a class="nav-link " href="dashboard-projects.html"><span class="nav-dot"></span>Projects</a></li>
            <li><a class="nav-link " href="dashboard-finance.html"><span class="nav-dot"></span>Finance</a></li>
          </ul>
        </li> --}}

              <!-- Apps Section -->
              <li class="nav-heading"><span>Operasional</span></li>

              <li class="nav-item has-submenu ">
                  <a class="nav-link" href="#" aria-expanded="false">
                      <span class="nav-icon"><i class="ph-light ph-calendar-check"></i></span>
                      <span class="nav-text">Kehadiran</span>
                      <span class="nav-arrow"><i class="bi bi-chevron-right"></i></span>
                  </a>
                  <ul class="nav-submenu ">
                      <li><a class="nav-link " href="components-alerts.html"><span class="nav-dot"></span>Setting
                              Kehadiran</a></li>
                      <li><a class="nav-link " href="components-accordion.html"><span class="nav-dot"></span>Shift
                              Kerja</a></li>
                      <li><a class="nav-link " href="components-badges.html"><span class="nav-dot"></span>Inputan
                              Kehadiran</a></li>
                      <li><a class="nav-link " href="components-breadcrumbs.html"><span
                                  class="nav-dot"></span>Monitoring Kehadiran</a></li>
                      <li><a class="nav-link " href="components-buttons.html"><span class="nav-dot"></span>Rekap
                              Kehadiran</a></li>
                  </ul>
              </li>

              <li class="nav-item">
                  <a class="nav-link " href="apps-calendar.html">
                      <span class="nav-icon"><i class="ph-light ph-chart-line-up"></i></span>
                      <span class="nav-text">Monitoring Kegiatan</span>
                  </a>
              </li>

              <li class="nav-item has-submenu ">
                  <a class="nav-link" href="#" aria-expanded="false">
                      <span class="nav-icon"><i class="ph-light ph-calendar-x"></i></span>
                      <span class="nav-text">Cuti</span>
                      <span class="nav-arrow"><i class="bi bi-chevron-right"></i></span>
                  </a>
                  <ul class="nav-submenu ">
                      <li><a class="nav-link " href="components-alerts.html"><span class="nav-dot"></span>Periode
                              Cuti</a></li>
                      <li><a class="nav-link " href="components-accordion.html"><span class="nav-dot"></span>Monitoring
                              Sisa Cuti</a></li>
                      <li><a class="nav-link " href="components-badges.html"><span class="nav-dot"></span>Permohonan
                              Cuti</a></li>
                      <li><a class="nav-link " href="components-breadcrumbs.html"><span
                                  class="nav-dot"></span>Permohonan Cuti</a></li>
                      <li><a class="nav-link " href="components-buttons.html"><span class="nav-dot"></span>Permohonan
                              izin</a></li>
                  </ul>
              </li>

              <li class="nav-item">
                  <a class="nav-link " href="apps-chat.html">
                      <span class="nav-icon"><i class="ph-light ph-newspaper"></i></span>
                      <span class="nav-text">Berita</span>
                  </a>
              </li>

              <li class="nav-item has-submenu ">
                  <a class="nav-link" href="#" aria-expanded="false">
                      <span class="nav-icon"><i class="ph-light ph-medal"></i></span>
                      <span class="nav-text">Kompensasi</span>
                      <span class="nav-arrow"><i class="bi bi-chevron-right"></i></span>
                  </a>
                  <ul class="nav-submenu ">
                      <li><a class="nav-link " href="components-alerts.html"><span
                                  class="nav-dot"></span>Penghargaan</a></li>
                      <li><a class="nav-link " href="components-accordion.html"><span
                                  class="nav-dot"></span>Pelanggaran</a></li>
                  </ul>
              </li>

              <li class="nav-item">
                  <a class="nav-link " href="apps-contacts.html">
                      <span class="nav-icon"><i class="ph-light ph-files"></i></span>
                      <span class="nav-text">Dokumen Internal</span>
                  </a>
              </li>

              <!-- Validasi -->
              <li class="nav-heading"><span>Validasi Data</span></li>
              <li class="nav-item">
                  <a class="nav-link {{ request()->routeIs('setting-validasi.*') ? 'active' : '' }}" href="{{ route('setting-validasi.index') }}">
                      <span class="nav-icon"><i class="ph-light ph-sliders-horizontal"></i></span>
                      <span class="nav-text">Setting Validasi</span>
                  </a>
              </li>

              <li class="nav-item">
                  <a class="nav-link {{ request()->routeIs('monitoring-validasi.*') ? 'active' : '' }}" href="{{ route('monitoring-validasi.index') }}">
                      <span class="nav-icon"><i class="ph-light ph-monitor"></i></span>
                      <span class="nav-text">Monitoring</span>
                  </a>
              </li>

              <li class="nav-item">
                  <a class="nav-link {{ request()->routeIs('validasi-keluarga.*') ? 'active' : '' }}" href="{{ route('validasi-keluarga.index') }}">
                      <span class="nav-icon"><i class="ph-light ph-users"></i></span>
                      <span class="nav-text">Keluarga</span>
                  </a>
              </li>

              <li class="nav-item has-submenu {{ request()->routeIs('validasi-homebase.*', 'validasi-pangkat.*', 'validasi-klasifikasi-sdm.*', 'validasi-jabatan-fungsional.*') ? 'open' : '' }}">
                  <a class="nav-link" href="#" aria-expanded="{{ request()->routeIs('validasi-homebase.*', 'validasi-pangkat.*', 'validasi-klasifikasi-sdm.*', 'validasi-jabatan-fungsional.*') ? 'true' : 'false' }}">
                      <span class="nav-icon"><i class="ph-light ph-identification-badge"></i></span>
                      <span class="nav-text">Kepegawaian</span>
                      <span class="nav-arrow"><i class="bi bi-chevron-right"></i></span>
                  </a>
                  <ul class="nav-submenu {{ request()->routeIs('validasi-homebase.*', 'validasi-pangkat.*', 'validasi-klasifikasi-sdm.*', 'validasi-jabatan-fungsional.*') ? 'show' : '' }}">
                      <li><a class="nav-link {{ request()->routeIs('validasi-homebase.*') ? 'active' : '' }}" href="{{ route('validasi-homebase.index') }}"><span
                                  class="nav-dot"></span>Homebase</a>
                      </li>
                      <li><a class="nav-link {{ request()->routeIs('validasi-pangkat.*') ? 'active' : '' }}" href="{{ route('validasi-pangkat.index') }}"><span
                                  class="nav-dot"></span>Pangkat</a></li>
                      <li><a class="nav-link {{ request()->routeIs('validasi-klasifikasi-sdm.*') ? 'active' : '' }}" href="{{ route('validasi-klasifikasi-sdm.index') }}"><span class="nav-dot"></span>Klasifikasi
                              SDM</a>
                      </li>
                      <li><a class="nav-link {{ request()->routeIs('validasi-jabatan-fungsional.*') ? 'active' : '' }}" href="{{ route('validasi-jabatan-fungsional.index') }}"><span
                                  class="nav-dot"></span>Jabatan Fungsional</a></li>
                      <li><a class="nav-link " href="components-buttons.html"><span class="nav-dot"></span>Jabatan
                              Struktural</a></li>
                      <li><a class="nav-link " href="components-cards.html"><span class="nav-dot"></span>Hubungan
                              Kerja</a>
                      </li>
                  </ul>
              </li>

              <li class="nav-item has-submenu ">
                  <a class="nav-link" href="#" aria-expanded="false">
                      <span class="nav-icon"><i class="ph-light ph-certificate"></i></span>
                      <span class="nav-text">Kompetensi</span>
                      <span class="nav-arrow"><i class="bi bi-chevron-right"></i></span>
                  </a>
                  <ul class="nav-submenu ">
                      <li><a class="nav-link " href="components-alerts.html"><span
                                  class="nav-dot"></span>Sertifikasi</a>
                      </li>
                      <li><a class="nav-link " href="components-accordion.html"><span class="nav-dot"></span>Tes</a>
                      </li>
                  </ul>
              </li>

              <li class="nav-item has-submenu ">
                  <a class="nav-link" href="#" aria-expanded="false">
                      <span class="nav-icon"><i class="ph-light ph-graduation-cap"></i></span>
                      <span class="nav-text">Pelaksanaan Pendidikan</span>
                      <span class="nav-arrow"><i class="bi bi-chevron-right"></i></span>
                  </a>
                  <ul class="nav-submenu ">
                      <li><a class="nav-link " href="components-alerts.html"><span class="nav-dot"></span>Visiting
                              Scientist</a>
                      </li>
                      <li><a class="nav-link " href="components-accordion.html"><span
                                  class="nav-dot"></span>Detasering</a>
                      </li>
                      <li><a class="nav-link " href="components-accordion.html"><span class="nav-dot"></span>Bahan
                              Ajar</a>
                      </li>
                      <li><a class="nav-link " href="components-accordion.html"><span class="nav-dot"></span>Orasi
                              Ilmiah</a>
                      </li>
                      <li><a class="nav-link " href="components-accordion.html"><span
                                  class="nav-dot"></span>Pembimbing Dosen</a>
                      </li>
                      <li><a class="nav-link " href="components-accordion.html"><span class="nav-dot"></span>Tugas
                              Tambahan</a>
                      </li>
                  </ul>
              </li>

              <li class="nav-item has-submenu ">
                  <a class="nav-link" href="#" aria-expanded="false">
                      <span class="nav-icon"><i class="ph-light ph-flask"></i></span>
                      <span class="nav-text">Pelaksanaan Penelitian</span>
                      <span class="nav-arrow"><i class="bi bi-chevron-right"></i></span>
                  </a>
                  <ul class="nav-submenu ">
                      <li><a class="nav-link " href="components-alerts.html"><span
                                  class="nav-dot"></span>Penelitian</a>
                      </li>
                      <li><a class="nav-link " href="components-accordion.html"><span
                                  class="nav-dot"></span>Publikasi</a>
                      </li>
                      <li><a class="nav-link " href="components-accordion.html"><span
                                  class="nav-dot"></span>Paten</a>
                      </li>
                  </ul>
              </li>

              <li class="nav-item has-submenu ">
                  <a class="nav-link" href="#" aria-expanded="false">
                      <span class="nav-icon"><i class="ph-light ph-handshake"></i></span>
                      <span class="nav-text">Pelaksanaan Pengabdian</span>
                      <span class="nav-arrow"><i class="bi bi-chevron-right"></i></span>
                  </a>
                  <ul class="nav-submenu ">
                      <li><a class="nav-link " href="components-alerts.html"><span
                                  class="nav-dot"></span>Pengabdian</a>
                      </li>
                      <li><a class="nav-link " href="components-accordion.html"><span
                                  class="nav-dot"></span>Pengelola Jurnal</a>
                      </li>
                      <li><a class="nav-link " href="components-accordion.html"><span
                                  class="nav-dot"></span>Pembicara</a>
                      </li>
                      <li><a class="nav-link " href="components-accordion.html"><span class="nav-dot"></span>Jabatan
                              Struktural</a>
                      </li>
                  </ul>
              </li>

              <li class="nav-item has-submenu ">
                  <a class="nav-link" href="#" aria-expanded="false">
                      <span class="nav-icon"><i class="ph-light ph-trophy"></i></span>
                      <span class="nav-text">Penunjang</span>
                      <span class="nav-arrow"><i class="bi bi-chevron-right"></i></span>
                  </a>
                  <ul class="nav-submenu ">
                      <li><a class="nav-link " href="components-alerts.html"><span
                                  class="nav-dot"></span>Pengabdian</a>
                      </li>
                      <li><a class="nav-link " href="components-accordion.html"><span class="nav-dot"></span>Anggota
                              Profesi</a>
                      </li>
                      <li><a class="nav-link " href="components-accordion.html"><span
                                  class="nav-dot"></span>Penghargaan</a>
                      </li>
                      <li><a class="nav-link " href="components-accordion.html"><span
                                  class="nav-dot"></span>Penunjang Lain</a>
                      </li>
                  </ul>
              </li>

              <li class="nav-item has-submenu ">
                  <a class="nav-link" href="#" aria-expanded="false">
                      <span class="nav-icon"><i class="ph-light ph-trend-up"></i></span>
                      <span class="nav-text">Pengembangan</span>
                      <span class="nav-arrow"><i class="bi bi-chevron-right"></i></span>
                  </a>
                  <ul class="nav-submenu ">
                      <li><a class="nav-link " href="components-alerts.html"><span
                                  class="nav-dot"></span>Pendidikan</a>
                      </li>
                      <li><a class="nav-link " href="components-accordion.html"><span class="nav-dot"></span>Sertifikasi</a>
                      </li>
                      <li><a class="nav-link " href="components-accordion.html"><span
                                  class="nav-dot"></span>Oraganisasi</a>
                      </li>
                      <li><a class="nav-link " href="components-accordion.html"><span
                                  class="nav-dot"></span>Penelitian</a>
                      </li>
                      <li><a class="nav-link " href="components-accordion.html"><span
                                  class="nav-dot"></span>Pengabdian Masyarakat</a>
                      </li>
                      <li><a class="nav-link " href="components-accordion.html"><span
                                  class="nav-dot"></span>Kemampuan Bahasa</a>
                      </li>
                      <li><a class="nav-link " href="components-accordion.html"><span
                                  class="nav-dot"></span>Tes</a>
                      </li>
                  </ul>
              </li>


              @if (Auth::user()?->hasPermission('referensi.view'))
              <!-- Pages Section -->
              <li class="nav-heading"><span>REFERENSI</span></li>

              <!-- Kepegawaian -->
              <li class="nav-item has-submenu {{ request()->routeIs('jenis-jabatan-struktural.*', 'jabatan-struktural.*', 'unit-kerja.*', 'jenis-sk.*', 'status-aktif.*', 'hubungan-kerja.*', 'pangkat.*', 'jenis-kenaikan-pangkat.*', 'klasifikasi-sdm.*', 'jabatan-fungsional.*', 'eselon.*', 'rumpun-bidang-ilmu.*', 'gelar-akademik.*') ? 'open' : '' }}">
                  <a class="nav-link" href="#" aria-expanded="{{ request()->routeIs('jenis-jabatan-struktural.*', 'jabatan-struktural.*', 'unit-kerja.*', 'jenis-sk.*', 'status-aktif.*', 'hubungan-kerja.*', 'pangkat.*', 'jenis-kenaikan-pangkat.*', 'klasifikasi-sdm.*', 'jabatan-fungsional.*', 'eselon.*', 'rumpun-bidang-ilmu.*', 'gelar-akademik.*') ? 'true' : 'false' }}">
                      <span class="nav-icon"><i class="ph-light ph-briefcase"></i></span>
                      <span class="nav-text">Kepegawaian</span>
                      <span class="nav-arrow"><i class="bi bi-chevron-right"></i></span>
                  </a>
                  <ul class="nav-submenu {{ request()->routeIs('jenis-jabatan-struktural.*', 'jabatan-struktural.*', 'unit-kerja.*', 'jenis-sk.*', 'status-aktif.*', 'hubungan-kerja.*', 'pangkat.*', 'jenis-kenaikan-pangkat.*', 'klasifikasi-sdm.*', 'jabatan-fungsional.*', 'eselon.*', 'rumpun-bidang-ilmu.*', 'gelar-akademik.*') ? 'show' : '' }}">
                      <li><a class="nav-link {{ request()->routeIs('unit-kerja.*') ? 'active' : '' }}" href="{{ route('unit-kerja.index') }}"><span class="nav-dot"></span>Unit Kerja</a>
                      </li>
                      <li><a class="nav-link {{ request()->routeIs('status-aktif.*') ? 'active' : '' }}" href="{{ route('status-aktif.index') }}"><span class="nav-dot"></span>Status Aktif</a>
                      </li>
                      <li><a class="nav-link {{ request()->routeIs('hubungan-kerja.*') ? 'active' : '' }}" href="{{ route('hubungan-kerja.index') }}"><span class="nav-dot"></span>Hubungan Kerja</a>
                      </li>
                      <li><a class="nav-link {{ request()->routeIs('pangkat.*') ? 'active' : '' }}" href="{{ route('pangkat.index') }}"><span class="nav-dot"></span>Pangkat</a></li>
                      <li><a class="nav-link {{ request()->routeIs('jenis-kenaikan-pangkat.*') ? 'active' : '' }}" href="{{ route('jenis-kenaikan-pangkat.index') }}"><span class="nav-dot"></span>Jenis Kenaikan
                              Pangkat</a></li>
                      <li><a class="nav-link {{ request()->routeIs('klasifikasi-sdm.*') ? 'active' : '' }}" href="{{ route('klasifikasi-sdm.index') }}"><span class="nav-dot"></span>Klasifikasi SDM</a>
                      </li>
                      <li><a class="nav-link {{ request()->routeIs('jabatan-fungsional.*') ? 'active' : '' }}" href="{{ route('jabatan-fungsional.index') }}"><span class="nav-dot"></span>Jabatan
                              Fungsional</a></li>
                      <li><a class="nav-link {{ request()->routeIs('eselon.*') ? 'active' : '' }}" href="{{ route('eselon.index') }}"><span class="nav-dot"></span>Eselon</a></li>
                      <li><a class="nav-link {{ request()->routeIs('jenis-jabatan-struktural.*') ? 'active' : '' }}" href="{{ route('jenis-jabatan-struktural.index') }}"><span class="nav-dot"></span>Jenis Jabatan
                              Struktural</a></li>
                      <li><a class="nav-link {{ request()->routeIs('jabatan-struktural.*') ? 'active' : '' }}" href="{{ route('jabatan-struktural.index') }}"><span class="nav-dot"></span>Jabatan Struktural</a>
                      </li>
                      <li><a class="nav-link {{ request()->routeIs('rumpun-bidang-ilmu.*') ? 'active' : '' }}" href="{{ route('rumpun-bidang-ilmu.index') }}"><span class="nav-dot"></span>Rumpun Bidang
                              Ilmu</a></li>
                      <li><a class="nav-link {{ request()->routeIs('jenis-sk.*') ? 'active' : '' }}" href="{{ route('jenis-sk.index') }}"><span class="nav-dot"></span>Jenis SK</a></li>
                      <li><a class="nav-link {{ request()->routeIs('gelar-akademik.*') ? 'active' : '' }}" href="{{ route('gelar-akademik.index') }}"><span class="nav-dot"></span>Gelar Akademik</a>
                      </li>
                  </ul>
              </li>

              <!-- Aktifitas -->
              <li class="nav-item has-submenu {{ request()->routeIs('jenis-sertifikasi.*', 'jenis-tes.*', 'jenis-pkm.*', 'output-penelitian.*', 'jenis-penghargaan.*', 'jenis-pelanggaran.*', 'jenis-luaran.*') ? 'open' : '' }}">
                  <a class="nav-link" href="#" aria-expanded="{{ request()->routeIs('jenis-sertifikasi.*', 'jenis-tes.*', 'jenis-pkm.*', 'output-penelitian.*', 'jenis-penghargaan.*', 'jenis-pelanggaran.*', 'jenis-luaran.*') ? 'true' : 'false' }}">
                      <span class="nav-icon"><i class="ph-light ph-list-checks"></i></span>
                      <span class="nav-text">Aktifitas</span>
                      <span class="nav-arrow"><i class="bi bi-chevron-right"></i></span>
                  </a>
                  <ul class="nav-submenu {{ request()->routeIs('jenis-sertifikasi.*', 'jenis-tes.*', 'jenis-pkm.*', 'output-penelitian.*', 'jenis-penghargaan.*', 'jenis-pelanggaran.*', 'jenis-luaran.*') ? 'show' : '' }}">
                      <li><a class="nav-link {{ request()->routeIs('jenis-sertifikasi.*') ? 'active' : '' }}" href="{{ route('jenis-sertifikasi.index') }}"><span class="nav-dot"></span>Jenis
                              Sertifikasi</a></li>
                      <li><a class="nav-link {{ request()->routeIs('jenis-tes.*') ? 'active' : '' }}" href="{{ route('jenis-tes.index') }}"><span class="nav-dot"></span>Jenis Tes</a></li>
                      <li><a class="nav-link {{ request()->routeIs('jenis-pkm.*') ? 'active' : '' }}" href="{{ route('jenis-pkm.index') }}"><span class="nav-dot"></span>Jenis Pengabdian
                              Masyarakat</a></li>
                      <li><a class="nav-link {{ request()->routeIs('output-penelitian.*') ? 'active' : '' }}" href="{{ route('output-penelitian.index') }}"><span class="nav-dot"></span>Output
                              Penelitian</a></li>
                      <li><a class="nav-link {{ request()->routeIs('jenis-penghargaan.*') ? 'active' : '' }}" href="{{ route('jenis-penghargaan.index') }}"><span class="nav-dot"></span>Jenis
                              Penghargaan</a></li>
                      <li><a class="nav-link {{ request()->routeIs('jenis-pelanggaran.*') ? 'active' : '' }}" href="{{ route('jenis-pelanggaran.index') }}"><span class="nav-dot"></span>Jenis
                              Pelanggaran</a></li>
                      <li><a class="nav-link {{ request()->routeIs('jenis-luaran.*') ? 'active' : '' }}" href="{{ route('jenis-luaran.index') }}"><span class="nav-dot"></span>Jenis
                              Luaran</a></li>
                  </ul>
              </li>

              <!-- Wilayah -->
              <li class="nav-item has-submenu {{ request()->routeIs('negara.*', 'provinsi.*', 'kabupaten-kota.*', 'kecamatan.*') ? 'open' : '' }}">
                  <a class="nav-link" href="#" aria-expanded="{{ request()->routeIs('negara.*', 'provinsi.*', 'kabupaten-kota.*', 'kecamatan.*') ? 'true' : 'false' }}">
                      <span class="nav-icon"><i class="ph-light ph-map-pin"></i></span>
                      <span class="nav-text">Wilayah</span>
                      <span class="nav-arrow"><i class="bi bi-chevron-right"></i></span>
                  </a>
                  <ul class="nav-submenu {{ request()->routeIs('negara.*', 'provinsi.*', 'kabupaten-kota.*', 'kecamatan.*') ? 'show' : '' }}">
                      <li><a class="nav-link {{ request()->routeIs('negara.*') ? 'active' : '' }}" href="{{ route('negara.index') }}"><span class="nav-dot"></span>Negara</a></li>
                      <li><a class="nav-link {{ request()->routeIs('provinsi.*') ? 'active' : '' }}" href="{{ route('provinsi.index') }}"><span class="nav-dot"></span>Provinsi</a></li>
                      <li><a class="nav-link {{ request()->routeIs('kabupaten-kota.*') ? 'active' : '' }}" href="{{ route('kabupaten-kota.index') }}"><span class="nav-dot"></span>Kabupaten/Kota</a>
                      </li>
                      <li><a class="nav-link {{ request()->routeIs('kecamatan.*') ? 'active' : '' }}" href="{{ route('kecamatan.index') }}"><span
                                  class="nav-dot"></span>Kecamatan</a></li>
                  </ul>
              </li>

              <!-- Kehadiran -->
              <li class="nav-item has-submenu {{ request()->routeIs('jenis-kehadiran.*', 'jenis-cuti.*', 'jenis-izin.*', 'jenis-hari.*', 'hari-libur.*', 'jam-kerja.*') ? 'open' : '' }}">
                  <a class="nav-link" href="#" aria-expanded="{{ request()->routeIs('jenis-kehadiran.*', 'jenis-cuti.*', 'jenis-izin.*', 'jenis-hari.*', 'hari-libur.*', 'jam-kerja.*') ? 'true' : 'false' }}">
                      <span class="nav-icon"><i class="ph-light ph-clock"></i></span>
                      <span class="nav-text">Kehadiran</span>
                      <span class="nav-arrow"><i class="bi bi-chevron-right"></i></span>
                  </a>
                  <ul class="nav-submenu {{ request()->routeIs('jenis-kehadiran.*', 'jenis-cuti.*', 'jenis-izin.*', 'jenis-hari.*', 'hari-libur.*', 'jam-kerja.*') ? 'show' : '' }}">
                      <li><a class="nav-link {{ request()->routeIs('jenis-kehadiran.*') ? 'active' : '' }}" href="{{ route('jenis-kehadiran.index') }}"><span class="nav-dot"></span>Jenis Kehadiran</a>
                      </li>
                      <li><a class="nav-link {{ request()->routeIs('jenis-cuti.*') ? 'active' : '' }}" href="{{ route('jenis-cuti.index') }}"><span class="nav-dot"></span>Jenis Cuti</a></li>
                      <li><a class="nav-link {{ request()->routeIs('jenis-izin.*') ? 'active' : '' }}" href="{{ route('jenis-izin.index') }}"><span class="nav-dot"></span>Jenis Izin</a></li>
                      <li><a class="nav-link {{ request()->routeIs('jenis-hari.*') ? 'active' : '' }}" href="{{ route('jenis-hari.index') }}"><span class="nav-dot"></span>Jenis
                              Hari</a></li>
                      <li><a class="nav-link {{ request()->routeIs('hari-libur.*') ? 'active' : '' }}" href="{{ route('hari-libur.index') }}"><span class="nav-dot"></span>Hari
                              Libur</a></li>
                      <li><a class="nav-link {{ request()->routeIs('jam-kerja.*') ? 'active' : '' }}" href="{{ route('jam-kerja.index') }}"><span class="nav-dot"></span>Jam
                              Kerja</a></li>
                  </ul>
              </li>

              <!-- Pelengkap -->
              <li class="nav-item has-submenu {{ request()->routeIs('agama.*', 'bahasa.*', 'pekerjaan.*', 'status-pernikahan.*', 'jenjang-pendidikan.*', 'perguruan-tinggi.*', 'prodi-perguruan-tinggi.*', 'bank.*', 'suku.*', 'golongan-darah.*') ? 'open' : '' }}">
                  <a class="nav-link" href="#" aria-expanded="{{ request()->routeIs('agama.*', 'bahasa.*', 'pekerjaan.*', 'status-pernikahan.*', 'jenjang-pendidikan.*', 'perguruan-tinggi.*', 'prodi-perguruan-tinggi.*', 'bank.*', 'suku.*', 'golongan-darah.*') ? 'true' : 'false' }}">
                      <span class="nav-icon"><i class="ph-light ph-folders"></i></span>
                      <span class="nav-text">Pelengkap</span>
                      <span class="nav-arrow"><i class="bi bi-chevron-right"></i></span>
                  </a>
                  <ul class="nav-submenu {{ request()->routeIs('agama.*', 'bahasa.*', 'pekerjaan.*', 'status-pernikahan.*', 'jenjang-pendidikan.*', 'perguruan-tinggi.*', 'prodi-perguruan-tinggi.*', 'bank.*', 'suku.*', 'golongan-darah.*') ? 'show' : '' }}">
                      <li><a class="nav-link {{ request()->routeIs('agama.*') ? 'active' : '' }}" href="{{ route('agama.index') }}"><span class="nav-dot"></span>Agama</a></li>
                      <li><a class="nav-link {{ request()->routeIs('bahasa.*') ? 'active' : '' }}" href="{{ route('bahasa.index') }}"><span class="nav-dot"></span>Bahasa</a></li>
                      <li><a class="nav-link {{ request()->routeIs('pekerjaan.*') ? 'active' : '' }}" href="{{ route('pekerjaan.index') }}"><span class="nav-dot"></span>Pekerjaan</a></li>
                      <li><a class="nav-link {{ request()->routeIs('status-pernikahan.*') ? 'active' : '' }}" href="{{ route('status-pernikahan.index') }}"><span class="nav-dot"></span>Status
                              Pernikahan</a></li>
                      <li><a class="nav-link {{ request()->routeIs('jenjang-pendidikan.*') ? 'active' : '' }}" href="{{ route('jenjang-pendidikan.index') }}"><span class="nav-dot"></span>Jenjang
                              Pendidikan</a></li>
                      <li><a class="nav-link {{ request()->routeIs('perguruan-tinggi.*') ? 'active' : '' }}" href="{{ route('perguruan-tinggi.index') }}"><span class="nav-dot"></span>Perguruan
                              Tinggi</a></li>
                      <li><a class="nav-link {{ request()->routeIs('prodi-perguruan-tinggi.*') ? 'active' : '' }}" href="{{ route('prodi-perguruan-tinggi.index') }}"><span class="nav-dot"></span>Prodi
                              Perguruan Tinggi</a></li>
                      <li><a class="nav-link {{ request()->routeIs('bank.*') ? 'active' : '' }}" href="{{ route('bank.index') }}"><span class="nav-dot"></span>Bank</a></li>
                      <li><a class="nav-link {{ request()->routeIs('suku.*') ? 'active' : '' }}" href="{{ route('suku.index') }}"><span class="nav-dot"></span>Suku</a></li>
                      <li><a class="nav-link {{ request()->routeIs('golongan-darah.*') ? 'active' : '' }}" href="{{ route('golongan-darah.index') }}"><span class="nav-dot"></span>Golongan
                              Darah</a></li>
                  </ul>
              </li>
              @endif

              @php
                  $canModul = Auth::user()?->hasPermission('modul.view');
                  $canUsers = Auth::user()?->hasPermission('users.view');
                  $canHakAkses = Auth::user()?->hasPermission('hak_akses.view');
                  $canBackup = Auth::user()?->hasPermission('backup.view');
              @endphp

              @if ($canModul || $canUsers || $canHakAkses || $canBackup)
              <!-- Pages Section -->
              <li class="nav-heading"><span>Pengaturan</span></li>

              @if ($canModul)
              <li class="nav-item">
                  <a class="nav-link {{ request()->routeIs('pengaturan.modul.*') ? 'active' : '' }}" href="{{ route('pengaturan.modul.index') }}">
                      <span class="nav-icon"><i class="bi bi-grid-1x2"></i></span>
                      <span class="nav-text">Pengaturan Modul</span>
                  </a>
              </li>
              @endif

              @if ($canUsers)
              <li class="nav-item">
                  <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                      <span class="nav-icon"><i class="ph-light ph-users-three"></i></span>
                      <span class="nav-text">Users</span>
                  </a>
              </li>
              @endif

              @if ($canHakAkses)
              <li class="nav-item">
                  <a class="nav-link {{ request()->routeIs('hak-akses.*') ? 'active' : '' }}" href="{{ route('hak-akses.index') }}">
                      <span class="nav-icon"><i class="bi bi-shield-lock"></i></span>
                      <span class="nav-text">Hak Akses</span>
                  </a>
              </li>
              @endif

              @if ($canBackup)
              <li class="nav-item">
                  <a class="nav-link {{ request()->routeIs('pengaturan.backup.*') ? 'active' : '' }}" href="{{ route('pengaturan.backup.index') }}">
                      <span class="nav-icon"><i class="bi bi-database-down"></i></span>
                      <span class="nav-text">Backup Database &amp; Storage</span>
                  </a>
              </li>
              @endif
              @endif
              
          </ul>
      </nav>

      <!-- Sidebar Footer -->
      <div class="sidebar-footer">
          <div class="sidebar-footer-user">
              <a href="{{ route('profile.index') }}" class="sidebar-footer-profile">
                  <img src="{{ Auth::user()?->avatar_url }}" alt="User" class="sidebar-footer-avatar">
                  <div class="sidebar-footer-info">
                      <div class="sidebar-footer-name">{{ Auth::user()->name ?? 'User' }}</div>
                      <div class="sidebar-footer-role">{{ ucwords(Auth::user()->roles ?? '') }}</div>
                  </div>
              </a>
              <div class="sidebar-footer-actions">
                  <a href="{{ route('pengaturan.modul.index') }}" class="sidebar-footer-action" title="Settings">
                      <i class="bi bi-gear"></i>
                  </a>
                  <a href="{{ route('logout') }}" class="sidebar-footer-action sidebar-footer-logout" title="Logout">
                      <i class="bi bi-box-arrow-right"></i>
                  </a>
              </div>
          </div>
          <a href="apps-support.html" class="sidebar-help-card">
              <span class="sidebar-help-icon"><i class="bi bi-headset"></i></span>
              <span class="sidebar-help-text">Need help? Open support desk</span>
              <i class="bi bi-arrow-up-right"></i>
          </a>
      </div>
  </aside>
