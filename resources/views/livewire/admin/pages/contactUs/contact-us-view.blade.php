<div>
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($wasUnread)
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            This message has been marked as read successfully.
            <button type="button" class="btn btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @elseif (!$contactUs->is_read->value)
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="fas fa-info-circle me-2"></i>
            This message is currently unread. It will be marked as read automatically.
            <button type="button" class="btn btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Contact Message</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Name:</strong>
                            <p>{{ $contactUs->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Email:</strong>
                            <p>{{ $contactUs->email }}</p>
                        </div>
                    </div>
                    
                    @if($contactUs->phone)
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Phone:</strong>
                            <p>{{ $contactUs->phone }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Subject:</strong>
                            <p>{{ $contactUs->subject ?? 'No subject' }}</p>
                        </div>
                    </div>
                    @else
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Subject:</strong>
                            <p>{{ $contactUs->subject ?? 'No subject' }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Date:</strong>
                            <p>{{ $contactUs->created_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                    @endif

                    <div class="mb-3">
                        <strong>Message:</strong>
                        <div class="border rounded p-3 bg-light">
                            {{ $contactUs->message }}
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.contact-us.edit', $contactUs) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>Edit
                        </a>
                        
                        <a href="{{ route('admin.contact-us.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to List
                        </a>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <strong>Status:</strong>
                        <div class="mt-2">
                            @if($contactUs->is_read->value)
                                <span class="badge bg-success">Read</span>
                            @else
                                <span class="badge bg-warning">Unread</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3">
                        <strong>Received:</strong>
                        <div class="mt-2">
                            <small class="text-muted">{{ $contactUs->created_at->format('M d, Y H:i') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Auto-refresh the parent table when returning from this view
    document.addEventListener('DOMContentLoaded', function() {
        // Check if we're coming from the table view
        if (document.referrer && document.referrer.includes('admin/contact-us')) {
            // Dispatch a custom event that the table can listen to
            window.dispatchEvent(new CustomEvent('contactUsViewed'));
        }
    });
</script>
