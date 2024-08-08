<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!-- User details -->
    

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">

            <li class="menu-title">Menu</li>

                            <li>
                                <a href="{{ route('dashboard') }}" class="waves-effect">
                                    <i class="ri-dashboard-line"></i><span class="badge rounded-pill bg-success float-end">3</span>
                                    <span>Dashboard</span>
                                </a>
                            </li>
               

        <li>
            <a href="javascript:void(0);" class="has-arrow waves-effect">
                <i class="ri-mail-send-line"></i>
                <span>Barang</span>
            </a>
            <ul class="sub-menu" aria-expanded="false">
                <li><a href="{{ route('kelompok.all') }}">Kelompok Barang</a></li>
                <li><a href="{{ route('barang.all') }}">Persediaan Barang</a></li>
            </ul>
        </li>
        

        <li>
            <a href="javascript: void(0);" class="has-arrow waves-effect">
                <i class="ri-mail-send-line"></i>
                <span>Permintaan</span>
            </a>
            <ul class="sub-menu" aria-expanded="false">
                <li><a href="{{ route('permintaan.saya') }}">Permintaan Saya</a></li>
                <li><a href="{{ route('pilihan.add') }}">Ajukan Permintaan</a></li>
                <li><a href="{{ route('permintaan.saya') }}">Permintaan Saya</a></li>
                <li><a href="{{ route('permintaan.all')}}">Semua Permintaan</a></li>
            </ul>
        </li>

        <li>
            <a href="javascript: void(0);" class="has-arrow waves-effect">
                <i class="ri-mail-send-line"></i>
                <span>Pengguna</span>
            </a>
            <ul class="sub-menu" aria-expanded="false">
                <li><a href="{{ route('user.all') }}">Seluruh Pengguna</a></li>
                {{-- <li><a href="{{ route('pilihan.add') }}">Ajukan Permintaan</a></li>
                <li><a href="{{ route('permintaan.all')}}">Semua Permintaan</a></li> --}}
            </ul>
        </li>
        
        </div>
    </div>
</div>