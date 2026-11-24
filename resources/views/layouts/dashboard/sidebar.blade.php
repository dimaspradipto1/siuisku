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
                      <span class="nav-icon"><i class="ph-light ph-puzzle-piece"></i></span>
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
                      <span class="nav-icon"><i class="ph-light ph-calendar-blank"></i></span>
                      <span class="nav-text">Motoring Kegiatan</span>
                  </a>
              </li>

              <li class="nav-item has-submenu ">
                  <a class="nav-link" href="#" aria-expanded="false">
                      <span class="nav-icon"><i class="ph-light ph-puzzle-piece"></i></span>
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
                      <span class="nav-icon"><i class="ph-light ph-chat-circle-dots"></i></span>
                      <span class="nav-text">Berita</span>
                  </a>
              </li>

              <li class="nav-item has-submenu ">
                  <a class="nav-link" href="#" aria-expanded="false">
                      <span class="nav-icon"><i class="ph-light ph-puzzle-piece"></i></span>
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
                      <span class="nav-icon"><i class="ph-light ph-address-book"></i></span>
                      <span class="nav-text">Dokumen Internal</span>
                  </a>
              </li>

              <!-- Validasi -->
              <li class="nav-heading"><span>Validasi Data</span></li>
              <li class="nav-item">
                  <a class="nav-link " href="contact.html">
                      <span class="nav-icon"><i class="ph-light ph-paper-plane-tilt"></i></span>
                      <span class="nav-text">Setting Validasi</span>
                  </a>
              </li>

              <li class="nav-item">
                  <a class="nav-link " href="contact.html">
                      <span class="nav-icon"><i class="ph-light ph-paper-plane-tilt"></i></span>
                      <span class="nav-text">Monitoring</span>
                  </a>
              </li>

              <li class="nav-item">
                  <a class="nav-link " href="contact.html">
                      <span class="nav-icon"><i class="ph-light ph-paper-plane-tilt"></i></span>
                      <span class="nav-text">Keluarga</span>
                  </a>
              </li>

              <li class="nav-item has-submenu ">
                  <a class="nav-link" href="#" aria-expanded="false">
                      <span class="nav-icon"><i class="ph-light ph-puzzle-piece"></i></span>
                      <span class="nav-text">Kepegawaian</span>
                      <span class="nav-arrow"><i class="bi bi-chevron-right"></i></span>
                  </a>
                  <ul class="nav-submenu ">
                      <li><a class="nav-link " href="components-alerts.html"><span
                                  class="nav-dot"></span>Homebase</a>
                      </li>
                      <li><a class="nav-link " href="components-accordion.html"><span
                                  class="nav-dot"></span>Pangkat</a></li>
                      <li><a class="nav-link " href="components-badges.html"><span class="nav-dot"></span>Klasifikasi
                              SDM</a>
                      </li>
                      <li><a class="nav-link " href="components-breadcrumbs.html"><span
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
                      <span class="nav-icon"><i class="ph-light ph-puzzle-piece"></i></span>
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
                      <span class="nav-icon"><i class="ph-light ph-puzzle-piece"></i></span>
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
                      <span class="nav-icon"><i class="ph-light ph-puzzle-piece"></i></span>
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
                      <span class="nav-icon"><i class="ph-light ph-puzzle-piece"></i></span>
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
                      <span class="nav-icon"><i class="ph-light ph-puzzle-piece"></i></span>
                      <span class="nav-text">Penunjang</span>
                      <span class="nav-arrow"><i class="bi bi-chevron-right"></i></span>
                  </a>
                  <ul class="nav-submenu ">
                      <li><a class="nav-link " href="components-alerts.html"><span
                                  class="nav-dot"></span>Pengabdian</a>
                      </li>
                      <li><a class="nav-link " href="components-accordion.html"><span class="nav-dot"></span>anggota
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
                      <span class="nav-icon"><i class="ph-light ph-puzzle-piece"></i></span>
                      <span class="nav-text">Pengambangan</span>
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


              <!-- Pages Section -->
              <li class="nav-heading"><span>REFERENSI</span></li>

              <!-- Kepegawaian -->
              <li class="nav-item has-submenu ">
                  <a class="nav-link" href="#" aria-expanded="false">
                      <span class="nav-icon"><i class="ph-light ph-receipt"></i></span>
                      <span class="nav-text">Kepegawaian</span>
                      <span class="nav-arrow"><i class="bi bi-chevron-right"></i></span>
                  </a>
                  <ul class="nav-submenu ">
                      <li><a class="nav-link " href="invoice-list.html"><span class="nav-dot"></span>Unit Kerja</a>
                      </li>
                      <li><a class="nav-link " href="invoice.html"><span class="nav-dot"></span>Status Keaktifan</a>
                      </li>
                      <li><a class="nav-link " href="invoice.html"><span class="nav-dot"></span>Hubungan Kerja</a>
                      </li>
                      <li><a class="nav-link " href="invoice.html"><span class="nav-dot"></span>Pangkat</a></li>
                      <li><a class="nav-link " href="invoice.html"><span class="nav-dot"></span>Jenis Kenaikan
                              Pangkat</a></li>
                      <li><a class="nav-link " href="invoice.html"><span class="nav-dot"></span>Kalsifikasi SDM</a>
                      </li>
                      <li><a class="nav-link " href="invoice.html"><span class="nav-dot"></span>Jabatan
                              Fungsional</a></li>
                      <li><a class="nav-link " href="invoice.html"><span class="nav-dot"></span>Esolon</a></li>
                      <li><a class="nav-link " href="invoice.html"><span class="nav-dot"></span>Jenis Jabatan
                              Struktural</a></li>
                      <li><a class="nav-link " href="invoice.html"><span class="nav-dot"></span>Jenis Struktural</a>
                      </li>
                      <li><a class="nav-link " href="invoice.html"><span class="nav-dot"></span>Rumpun Bidang
                              Ilmu</a></li>
                      <li><a class="nav-link " href="invoice.html"><span class="nav-dot"></span>Jenis SK</a></li>
                      <li><a class="nav-link " href="invoice.html"><span class="nav-dot"></span>Gelar Akademik</a>
                      </li>
                  </ul>
              </li>

              <!-- Aktifitas -->
              <li class="nav-item has-submenu ">
                  <a class="nav-link" href="#" aria-expanded="false">
                      <span class="nav-icon"><i class="ph-light ph-warning"></i></span>
                      <span class="nav-text">Aktifitas</span>
                      <span class="nav-arrow"><i class="bi bi-chevron-right"></i></span>
                  </a>
                  <ul class="nav-submenu ">
                      <li><a class="nav-link " href="error-404.html"><span class="nav-dot"></span>Jenis
                              Sertifikasi</a></li>
                      <li><a class="nav-link " href="error-403.html"><span class="nav-dot"></span>Jenis Tes</a></li>
                      <li><a class="nav-link " href="error-500.html"><span class="nav-dot"></span>Jenis Pengabdian
                              Masyarakat</a></li>
                      <li><a class="nav-link " href="error-maintenance.html"><span class="nav-dot"></span>Output
                              Penelitian</a></li>
                      <li><a class="nav-link " href="error-coming-soon.html"><span class="nav-dot"></span>Jenis
                              Penghargaan</a></li>
                      <li><a class="nav-link " href="error-coming-soon.html"><span class="nav-dot"></span>Jenis
                              Pelanggaran</a></li>
                      <li><a class="nav-link " href="error-coming-soon.html"><span class="nav-dot"></span>Jenis
                              Luaran</a></li>
                  </ul>
              </li>

              <!-- Wilayah -->
              <li class="nav-item has-submenu ">
                  <a class="nav-link" href="#" aria-expanded="false">
                      <span class="nav-icon"><i class="ph-light ph-warning"></i></span>
                      <span class="nav-text">Wilayah</span>
                      <span class="nav-arrow"><i class="bi bi-chevron-right"></i></span>
                  </a>
                  <ul class="nav-submenu ">
                      <li><a class="nav-link " href="error-404.html"><span class="nav-dot"></span>Negara</a></li>
                      <li><a class="nav-link " href="error-403.html"><span class="nav-dot"></span>Provinsi</a></li>
                      <li><a class="nav-link " href="error-500.html"><span class="nav-dot"></span>Kabutpaten/Kota</a>
                      </li>
                      <li><a class="nav-link " href="error-maintenance.html"><span
                                  class="nav-dot"></span>Kecamatan</a></li>
                  </ul>
              </li>

              <!-- Kehadiran -->
              <li class="nav-item has-submenu ">
                  <a class="nav-link" href="#" aria-expanded="false">
                      <span class="nav-icon"><i class="ph-light ph-warning"></i></span>
                      <span class="nav-text">Kehadiran</span>
                      <span class="nav-arrow"><i class="bi bi-chevron-right"></i></span>
                  </a>
                  <ul class="nav-submenu ">
                      <li><a class="nav-link " href="error-404.html"><span class="nav-dot"></span>Jenis Kehadiran</a>
                      </li>
                      <li><a class="nav-link " href="error-403.html"><span class="nav-dot"></span>Jenis Cuti</a></li>
                      <li><a class="nav-link " href="error-500.html"><span class="nav-dot"></span>Jenis Izin</a></li>
                      <li><a class="nav-link " href="error-maintenance.html"><span class="nav-dot"></span>Jenis
                              Hari</a></li>
                      <li><a class="nav-link " href="error-maintenance.html"><span class="nav-dot"></span>Hari
                              Libur</a></li>
                      <li><a class="nav-link " href="error-maintenance.html"><span class="nav-dot"></span>Jam
                              Kerja</a></li>
                  </ul>
              </li>

              <!-- Pelengkap -->
              <li class="nav-item has-submenu ">
                  <a class="nav-link" href="#" aria-expanded="false">
                      <span class="nav-icon"><i class="ph-light ph-warning"></i></span>
                      <span class="nav-text">Pelengkap</span>
                      <span class="nav-arrow"><i class="bi bi-chevron-right"></i></span>
                  </a>
                  <ul class="nav-submenu ">
                      <li><a class="nav-link " href="error-404.html"><span class="nav-dot"></span>Agama</a></li>
                      <li><a class="nav-link " href="error-403.html"><span class="nav-dot"></span>Bahasa</a></li>
                      <li><a class="nav-link " href="error-500.html"><span class="nav-dot"></span>Pekerjaan</a></li>
                      <li><a class="nav-link " href="error-maintenance.html"><span class="nav-dot"></span>Status
                              Pernikahan</a></li>
                      <li><a class="nav-link " href="error-maintenance.html"><span class="nav-dot"></span>Jenjang
                              Pendidiikan </a></li>
                      <li><a class="nav-link " href="error-maintenance.html"><span class="nav-dot"></span>Perguruan
                              Tinggi</a></li>
                      <li><a class="nav-link " href="error-maintenance.html"><span class="nav-dot"></span>Prodi
                              Perguruan Tinggi</a></li>
                      <li><a class="nav-link " href="error-maintenance.html"><span class="nav-dot"></span>Bank</a>
                      </li>
                      <li><a class="nav-link " href="error-maintenance.html"><span class="nav-dot"></span>Suku</a>
                      </li>
                      <li><a class="nav-link " href="error-maintenance.html"><span class="nav-dot"></span>Golongan
                              Darah</a></li>
                      <li><a class="nav-link " href="error-maintenance.html"><span class="nav-dot"></span>Gelar
                              Akademik</a></li>
                  </ul>
              </li>

              <!-- Pages Section -->
              <li class="nav-heading"><span>Pengaturan</span></li>

              <li class="nav-item">
                  <a class="nav-link {{ request()->routeIs('pengaturan.modul.*') ? 'active' : '' }}" href="{{ route('pengaturan.modul.index') }}">
                      <span class="nav-icon"><i class="bi bi-grid-1x2"></i></span>
                      <span class="nav-text">Pengaturan Modul</span>
                  </a>
              </li>

              <li class="nav-item">
                  <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                      <span class="nav-icon"><i class="ph-light ph-users-three"></i></span>
                      <span class="nav-text">Users</span>
                  </a>
              </li>

              <li class="nav-item">
                  <a class="nav-link {{ request()->routeIs('pengaturan.backup.*') ? 'active' : '' }}" href="{{ route('pengaturan.backup.index') }}">
                      <span class="nav-icon"><i class="bi bi-database-down"></i></span>
                      <span class="nav-text">Backup Database &amp; Storage</span>
                  </a>
              </li>
              
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
