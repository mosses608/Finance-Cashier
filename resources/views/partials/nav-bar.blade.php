@if (Auth::check())
    <div class="sidebar" ata-background-color="dark" style="background-color: #00a670;">
        <div class="sidebar-logo">
            <div class="logo-header px-5">
                <a href="{{ route('home') }}" class="logo">
                    <img width="110" src="{{ asset('assets/images/akilisoft-logo-image.png') }}" alt="navbar brand"
                        class="navbar-brand" height="70" />
                </a>
                <div class="nav-toggle">
                    <button class="btn btn-toggle toggle-sidebar">
                        <i class="gg-menu-right text-white"></i>
                    </button>
                    <button class="btn btn-toggle sidenav-toggler">
                        <i class="gg-menu-left text-white"></i>
                    </button>
                </div>
                <button class="topbar-toggler more">
                    <i class="gg-more-vertical-alt"></i>
                </button>
            </div>
        </div>

        <div class="sidebar-wrapper scrollbar scrollbar-inner">
            <div class="sidebar-content">
                <ul class="nav nav-secondary">
                    <li class="nav-item">
                        <a href="{{ route('home') }}" class="collapsed">
                            <i class="fas fa-home"></i>
                            <p>Dashboard</p>
                            <span class="caret"></span>
                        </a>
                    </li>

                    @foreach ($parentModules->where('module_parent_id', null) as $parent)
                        @php
                            $children = $childModules->where('parent_module_id', $parent->module_id);

                            $collapseId = 'collapse-' . $parent->module_id;
                        @endphp

                        <li class="nav-item">
                            <a data-bs-toggle="collapse" href="#{{ $collapseId }}" role="button"
                                aria-expanded="false" aria-controls="{{ $collapseId }}">
                                {!! $parent->module_icon !!}
                                <p>{{ $parent->module_name ?? '' }}</p>
                                <span class="caret"></span>
                            </a>
                            <div class="collapse" id="{{ $collapseId }}">
                                <ul class="nav nav-collapse">
                                    @foreach ($children as $child)
                                        <li>
                                            <a href="/{{ $child->module_path }}">
                                                <span class="sub-item">{{ $child->module_name }}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@else
    <span class="text-danger">Please login to access this resource!</span>
@endif
