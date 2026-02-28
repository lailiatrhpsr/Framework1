<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
    <li class="nav-item nav-profile">
      <a href="#" class="nav-link">
        <div class="nav-profile-text d-flex flex-column">
          <span class="font-weight-bold mb-2">Koleksi Buku</span>
          <span class="text-secondary text-small">Menu Utama</span>
        </div>
        <i class="mdi mdi-book-open-page-variant text-success nav-profile-badge"></i>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="{{ route('home') }}">
        <span class="menu-title">Dashboard</span>
        <i class="mdi mdi-home menu-icon"></i>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="{{ route('buku.index') }}">
        <span class="menu-title">Buku</span>
        <i class="mdi mdi-book menu-icon"></i>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="{{ route('kategori.index') }}">
        <span class="menu-title">Kategori</span>
        <i class="mdi mdi-format-list-bulleted menu-icon"></i>
      </a>
    </li>

    <li class="nav-item nav-category" style="margin-top: 15px; padding-left: 20px;">
        <span class="text-muted small text-uppercase">PDF</span>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="{{ route('sertifikat.index') }}">
        <span class="menu-title">Cetak Sertifikat</span>
        <i class="mdi mdi-certificate menu-icon"></i>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="{{ route('undangan.download') }}">
        <span class="menu-title">Cetak Undangan</span>
        <i class="mdi mdi-email-open menu-icon"></i>
      </a>
    </li>
  </ul>
</nav>