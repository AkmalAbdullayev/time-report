<div class="header-navbar-shadow"></div>
<nav class="header-navbar main-header-navbar navbar-expand-lg navbar navbar-with-menu fixed-top">
    <div class="navbar-wrapper">
        <div class="navbar-container content">
            <div class="navbar-collapse" id="navbar-mobile">
                <div class="mr-auto float-left bookmark-wrapper d-flex align-items-center">
                    <ul class="nav navbar-nav">
                        <li class="nav-item mobile-menu d-xl-none mr-auto">
                            <a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#">
                                <i class="ficon bx bx-menu"></i>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav bookmark-icons">
                        <li class="nav-item d-none d-lg-block">
                            <a class="btn btn-outline-primary" style="margin-left: 5px;" href="{{url("admin/users")}}">
                                <span class="menu-item">Пользователи</span>
                            </a>
                        </li>
                        <li class="nav-item d-none d-lg-block">
                            <a class="btn btn-outline-primary" style="margin-left: 5px;"
                               href="{{url("admin/employees")}}">
                                <span class="menu-item">Сотрудники</span>
                            </a>
                        </li>
                        <li class="nav-item d-none d-lg-block">
                            <a class="btn btn-outline-primary" style="margin-left: 5px;"
                               href="{{url("admin/devices")}}">
                                <span class="menu-item">Устройства</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <ul class="nav navbar-nav float-right">
                    {{--                    <li class="dropdown dropdown-language nav-item">--}}
                    {{--                        <a--}}
                    {{--                            href="#"--}}
                    {{--                            class="dropdown-toggle nav-link"--}}
                    {{--                            id="dropdown-flag"--}}
                    {{--                            data-toggle="dropdown"--}}
                    {{--                            aria-haspopup="true"--}}
                    {{--                            aria-expanded="false"--}}
                    {{--                            style="margin-right: 30px;"--}}
                    {{--                        >--}}
                    {{--                            <i class="flag-icon flag-icon-us"></i>--}}
                    {{--                            <span class="selected-language">{{ config("app.locale") }}</span>--}}
                    {{--                        </a>--}}

                    {{--                        <div class="dropdown-menu" aria-labelledby="dropdown-flag">--}}
                    {{--                            <a href="" class="dropdown-item" data-language="en">--}}
                    {{--                                <i class="flag-icon flag-icon-us mr-50"></i>--}}
                    {{--                                English--}}
                    {{--                            </a>--}}
                    {{--                            <a href="" class="dropdown-item" data-language="ru">--}}
                    {{--                                <i class="flag-icon flag-icon-ru mr-50"></i>--}}
                    {{--                                Русский--}}
                    {{--                            </a>--}}
                    {{--                        </div>--}}
                    {{--                    </li>--}}
                    <li class="dropdown dropdown-user nav-item"><a class="dropdown-toggle nav-link dropdown-user-link"
                                                                   href="#" data-toggle="dropdown">
                            <div class="user-nav d-sm-flex d-none">
                                <span class="user-name">{{auth()->user()->name}}</span>
                            </div>
                            <span>
                                <span class="bx bxs-user" style="font-size:30px;"></span>
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right pb-0">
                            <a class="dropdown-item" href=""><i class="bx bx-user mr-50"></i> Настройки</a>
                            <div class="dropdown-divider mb-0"></div>
                            <a
                                class="dropdown-item confirmDelete"
                                href="{{ route('logout') }}"
                                data-form="logout"
                                onclick="event.preventDefault(); document.getElementById('logout').submit();"
                            >
                                <i class="bx bx-power-off mr-50"></i> Выход
                                <form action="{{ route('logout') }}" method="post" id="logout">
                                    @csrf
                                </form>
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
