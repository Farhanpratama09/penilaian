  <!-- Navbar -->

  <nav class="layout-navbar container-fluid navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
      id="layout-navbar">
      <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
          <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
              <i class="bx bx-menu bx-sm"></i>
          </a>
      </div>

      <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
          <div class="marquee-container">
              <div class="marquee-content">
                  Selamat Datang, {{ auth()->user()->nama }}!
              </div>
          </div>
          <ul class="navbar-nav flex-row align-items-center ms-auto">
              <!-- Place this tag where you want the button to render. -->

              <!-- User -->
              <li class="nav-item navbar-dropdown dropdown-user dropdown">
                  <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                      <span class="ms-2 d-none d-sm-block user-item-desc">
                          <span class="user-name">{{ auth()->user()->nama }}</span>
                          <span class="user-sub-title">{{ auth()->user()->username }}</span>
                          {{-- @if (auth()->user()->role == 1)
                              <span class="user-sub-title">Admin</span>
                          @elseif (auth()->user()->role == 2)
                              <span class="user-sub-title">Kepala Sekolah</span>
                          @elseif (auth()->user()->role == 3)
                              <span class="user-sub-title">Guru</span>
                          @endif --}}
                      </span>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end">
                      <li>
                          <a class="dropdown-item" href="{{ route('dashboard_guru') }}">
                              <div class="d-flex">
                                  <div class="flex-grow-1">
                                      <span class="fw-semibold d-block">{{ auth()->user()->nama }}</span>
                                      <small class="text-muted">{{ auth()->user()->username }}</small>
                                  </div>
                              </div>
                          </a>
                      </li>
                      <li>
                          <a class="dropdown-item" href="{{ route('guru_profil') }}">
                              <i class="bx bx-user me-2"></i>
                              <span class="align-middle">My Profile</span>
                          </a>
                      </li>
                      <li>
                          <form method="POST" action="{{ route('logout') }}">
                              @csrf
                              <button type="submit" id="logout" style="display: none"></button>
                          </form>
                          <a class="dropdown-item" href="#" onclick="document.getElementById('logout').click();">
                              <i class="bx bx-power-off me-2"></i>
                              <span class="align-middle">Log Out</span>
                          </a>
                      </li>
                  </ul>
              </li>
              <!--/ User -->
          </ul>
      </div>
  </nav>

  <style>
      /* Mengatur ukuran dan posisi dari nama pengguna */
      .user-name {
          font-size: 18px;
          font-weight: bold;
      }

      /* Mengatur ukuran dan posisi dari subjudul pengguna */
      .user-sub-title {
          font-size: 12px;
          color: #888;
          display: block;
          margin-top: -5px;
      }

      /* Mengatur gaya avatar dan nama pengguna di dropdown */
      .dropdown-user .dropdown-item .flex-grow-1 {
          display: flex;
          flex-direction: column;
          align-items: flex-start;
      }

      /* Mengatur margin antara nama pengguna dan email */
      .dropdown-user .dropdown-item .flex-grow-1 span {
          margin-bottom: 2px;
      }

      .marquee-container {
          overflow: hidden;
          white-space: nowrap;
          width: 300px;
          /* Sesuaikan ukuran sesuai kebutuhan */
          margin-right: 20px;
          /* Agar tidak terlalu dekat dengan elemen lain */
      }

      .marquee-content {
          display: inline-block;
          padding-left: 100%;
          /* Mulai dari luar layar */
          animation: marquee 10s linear infinite;
          /* Atur kecepatan sesuai kebutuhan */
      }

      @keyframes marquee {
          from {
              transform: translateX(0%);
          }

          to {
              transform: translateX(-100%);
          }
      }
  </style>
  <script>
      const marquee = document.querySelector('.marquee-content');

      marquee.addEventListener('mouseover', () => {
          marquee.style.animationPlayState = 'paused';
      });

      marquee.addEventListener('mouseout', () => {
          marquee.style.animationPlayState = 'running';
      });
  </script>



  <!-- / Navbar -->
