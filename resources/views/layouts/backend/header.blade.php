<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
        <i class="ri-menu-fill ri-22px"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <ul class="navbar-nav flex-row align-items-center ms-auto">
        <!-- Notification -->
        <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-4 me-xl-1">
            <a
            class="nav-link btn btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow"
            href="javascript:void(0);"
            data-bs-toggle="dropdown"
            data-bs-auto-close="outside"
            aria-expanded="false">
                <i class="ri-notification-2-line ri-22px"></i>
                @if (auth()->user()->unreadNotifications->count() > 0)
                    <span class="position-absolute top-0 start-50 translate-middle-y badge badge-dot bg-danger mt-2 border"></span>
                @endif
            </a>
            <ul class="dropdown-menu dropdown-menu-end py-0">
                <li class="dropdown-menu-header border-bottom py-50">
                    <div class="dropdown-header d-flex align-items-center py-2">
                    <h6 class="mb-0 me-auto">Notifikasi</h6>
                    <div class="d-flex align-items-center">
                        <span class="badge rounded-pill bg-label-primary fs-xsmall me-2">{{ auth()->user()->unreadNotifications->count() }}</span>
                        <form action="{{ route('notifications.markAllRead') }}" method="post">
                            @csrf
                            @method('PATCH')
                            <button
                            type="submit"
                            class="btn btn-text-secondary rounded-pill btn-icon dropdown-notifications-all"
                            data-bs-toggle="tooltip"
                            data-bs-placement="top"
                            title="Tandai Sudah Dibaca Semua">
                            <i class="ri-mail-open-line text-heading ri-20px"></i>
                            </button>
                        </form>
                        {{-- <a
                        href="javascript:void(0)"
                        class="btn btn-text-secondary rounded-pill btn-icon dropdown-notifications-all"
                        data-bs-toggle="tooltip"
                        data-bs-placement="top"
                        title="Mark all as read"
                        ><i class="ri-mail-open-line text-heading ri-20px"></i
                        ></a> --}}
                    </div>
                    </div>
                </li>
                <li class="dropdown-notifications-list scrollable-container">
                    <ul class="list-group list-group-flush">
                        @foreach (auth()->user()->notifications as $notification)
                            <li class="list-group-item list-group-item-action dropdown-notifications-item">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <a href="{{ $notification->data['url'] }}">
                                            <h6 class="small mb-1">{{ $notification->data['title'] }}</h6>
                                            <small class="mb-1 d-block text-body">{{ $notification->data['message'] }}</small>
                                            <div class="d-flex flex-column">
                                                <small class="text-danger">{{ $notification->data['priority'] }}</small>
                                                <small class="text-muted">{{ $notification->data['status'] }}</small>
                                            </div>
                                            <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                        </a>
                                    </div>
                                    <div class="flex-shrink-0 dropdown-notifications-actions">
                                        @if(is_null($notification->read_at))
                                            <form method="POST" action="{{ route('notifications.markAsRead',$notification->id) }}">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="id" value="{{ $notification->id }}">
                                                <button type="submit" class="btn btn-success">
                                                    <i class="ri-checkbox-circle-line"></i>
                                                </button>
                                            </form>
                                        @else
                                            <span><i class="ri-check-double-line"></i></span>
                                        @endif
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </li>
            </ul>
        </li>
        <!--/ Notification -->

        <!-- User -->
        <li class="nav-item navbar-dropdown dropdown-user dropdown">
            <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
            <div class="avatar avatar-online">
                <img src="{{ asset('assets/img/avatars/1.png') }}" alt="Foto Profile {{ auth()->user()->name }}" class="rounded-circle" />
            </div>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
            <li>
                <a class="dropdown-item" href="{{ route('profile.index') }}">
                <div class="d-flex">
                    <div class="flex-shrink-0 me-2">
                    <div class="avatar avatar-online">
                        <img src="{{ asset('assets/img/avatars/1.png') }}" alt="Foto Profile {{ auth()->user()->name }}" class="rounded-circle" />
                    </div>
                    </div>
                    <div class="flex-grow-1">
                    <span class="fw-medium d-block small">{{ auth()->user()->name }}</span>
                    <small class="text-muted">{{ auth()->user()->roles()->first()->name }}</small>
                    </div>
                </div>
                </a>
            </li>
            <li>
                <div class="dropdown-divider"></div>
            </li>
            <li>
                <a class="dropdown-item" href="{{ route('profile.index') }}">
                <i class="ri-user-3-line ri-22px me-3"></i><span class="align-middle">My Profile</span>
                </a>
            </li>
            <li>
                <a class="dropdown-item" href="{{ route('profile.setting') }}">
                <i class="ri-settings-4-line ri-22px me-3"></i><span class="align-middle">Settings Pasword</span>
                </a>
            </li>
            <li>
                <div class="dropdown-divider"></div>
            </li>
            <li>
                <div class="d-grid px-4 pt-2 pb-1">
                    <a class="btn btn-sm btn-danger d-flex" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <small class="align-middle">Logout</small>
                        <i class="ri-logout-box-r-line ms-2 ri-16px"></i>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </li>
            </ul>
        </li>
        <!--/ User -->
        </ul>
    </div>
</nav>
