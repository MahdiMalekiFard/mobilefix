<div>
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded">
                                <span class="avatar-title bg-primary bg-opacity-10 rounded">
                                    <i class="fas fa-envelope text-primary" style="font-size: 24px;"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-medium text-muted mb-1">Total Messages</p>
                            <h4 class="mb-0">{{ $contactStats['total'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded">
                                <span class="avatar-title bg-warning bg-opacity-10 rounded">
                                    <i class="fas fa-eye-slash text-warning" style="font-size: 24px;"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-medium text-muted mb-1">Unread</p>
                            <h4 class="mb-0">{{ $contactStats['unread'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded">
                                <span class="avatar-title bg-danger bg-opacity-10 rounded">
                                    <i class="fas fa-reply text-danger" style="font-size: 24px;"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-medium text-muted mb-1">Unreplied</p>
                            <h4 class="mb-0">{{ $contactStats['unreplied'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded">
                                <span class="avatar-title bg-success bg-opacity-10 rounded">
                                    <i class="fas fa-calendar-day text-success" style="font-size: 24px;"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-medium text-muted mb-1">Today</p>
                            <h4 class="mb-0">{{ $contactStats['today'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($contactStats['unread'] > 0 || $contactStats['unreplied'] > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex gap-2">
                        @if($contactStats['unread'] > 0)
                        <a href="{{ route('admin.contact-us.index') }}?filter[is_read_formatted]=0" class="btn btn-warning">
                            <i class="fas fa-eye me-2"></i>View Unread Messages
                        </a>
                        @endif
                        
                        @if($contactStats['unreplied'] > 0)
                        <a href="{{ route('admin.contact-us.index') }}?filter[is_replied_formatted]=0" class="btn btn-danger">
                            <i class="fas fa-reply me-2"></i>View Unreplied Messages
                        </a>
                        @endif
                        
                        <a href="{{ route('admin.contact-us.index') }}" class="btn btn-primary">
                            <i class="fas fa-list me-2"></i>View All Messages
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
