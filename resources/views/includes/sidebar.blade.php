<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element">
                    <center>
                        <div class="avatar-container rounded-circle">
                            <img src="{{ asset('storage/user/' . Auth::user()->avatar) }}" class="avatar-img"
                                alt="Avatar">
                        </div>
                    </center>
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <span class="block m-t-xs font-bold">{{ Auth::user()->name }}</span>
                        <span class=" text-xs block">{{ Auth::user()->role }}<b class="caret"></b></span>
                    </a>
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">
                        {{-- <li>
                            <a href="{{ route('profile.index') }}"
                                class="border-0 dropdown-item p-2 bg-transparent logout">Profile</a>
                        </li> --}}
                        <form action="{{ route('logout') }}" method="post">
                            @csrf
                            <li>
                                <button class="border-0 dropdown-item p-2 bg-transparent logout">Log out</button>
                            </li>
                        </form>
                    </ul>
                </div>
                <div class="logo-element">
                    Blug
                </div>
            </li>
            <li class="{{ Request::path() == '/dashboard' ? 'active' : '' }}">
                <a href="/dashboard"><i class="fa fa-th-large"></i> <span class="nav-label">Dashboard</span></a>
            </li>
            <li class="{{ Request::path() == '/transaksi' ? 'active' : '' }}">
                <a href="/transaksi"><i class="fa fa-clipboard"></i> <span class="nav-label">Transaksi</span>
                </a>
            </li>
            <li class="{{ Request::path() == '/produk' ? 'active' : '' }}">
                <a href="/produk"><i class="fa fa-square"></i> <span class="nav-label">Produk</span>
                </a>
            </li>
            <li class="{{ Request::path() == '/kategori' ? 'active' : '' }}">
                <a href="/kategori"><i class="fa fa-list"></i> <span class="nav-label">Kategori</span> </a>
            </li>
            <li class="{{ Request::path() == '/user' ? 'active' : '' }}">
                <a href="/user"><i class="fa fa-user"></i> <span class="nav-label">Users</span><span
                        class="fa arrow"></span> </a>
                <ul class="nav nav-second-level collapse">
                    <li><a href="/user-penjual">Penjual</a></li>
                    <li><a href="/user-pembeli">Pembeli</a></li>
                </ul>
            </li>
            {{-- <li class="{{ Request::path() == 'product-name' ? 'active' : '' }}">
                <a href="/product-name"><i class="fa fa-tags"></i> <span class="nav-label"> Nama Produk</span> </a>
            </li>
            <li class="{{ Request::path() == 'monitoring' ? 'active' : '' }}">
                <a href="/monitoring"><i class="fa fa-desktop"></i> <span class="nav-label">Monitoring
                        Penjual</span>
                </a>
            </li> --}}
            {{-- <li class="{{ Request::path() == 'contact' ? 'active' : '' }}">
                <a href="/contact"><i class="fa fa-phone"></i> <span class="nav-label">Kelola Kontak</span> </a>
            </li> --}}
            {{-- <li class="{{ Request::path() == '/user' ? 'active' : '' }}">
                <a href="/user"><i class="fa fa-user"></i> <span class="nav-label">Kelola User</span><span
                        class="fa arrow"></span> </a>
                <ul class="nav nav-second-level collapse">
                    <li><a href="/user-customer">Customer</a></li>
                    <li><a href="/user-seller">Seller</a></li>
                    <li><a href="/user-driver">Driver</a></li>
                </ul>
            </li> --}}
        </ul>

    </div>
</nav>
