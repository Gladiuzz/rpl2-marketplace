<div class="row border-bottom">
    <nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
        </div>
        <ul class="nav navbar-top-links navbar-right">
            <li>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="border-0 bg-transparent font-weight-bold logout">
                        <i class="fa fa-sign-out"></i> Log out
                    </button>
                </form>
            </li>
        </ul>

    </nav>
</div>
