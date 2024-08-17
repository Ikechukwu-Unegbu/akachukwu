<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h4>Create New Announcement</h4>
            <a href="{{route('admin.announcement.index')}}">View All</a>
        </div>
        <div class="card-body">
            <form wire:submit="save">
                <!-- CSRF Token (if using Laravel) -->
                <input type="hidden"  name="_token" value="{{ csrf_token() }}">
                <!-- Check if there is a status message in the session -->
                @if(session('status'))
                    <div class="container mt-3">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                @endif


                <!-- Title -->
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text"  wire:model="title" class="form-control" id="title" name="title" required>
                </div>

                <!-- Message -->
                <div class="mb-3">
                    <label for="message" class="form-label">Message</label>
                    <textarea wire:model="message" class="form-control" id="message" name="message" rows="4" required></textarea>
                </div>

                <!-- Type -->
                <div class="mb-3">
                    <label for="type" class="form-label">Type</label>
                    <select class="form-select" wire:model="type" id="type" name="type" required>
                        <option default value="info">Info</option>
                        <option value="warning">Warning</option>
                        <option value="success">Success</option>
                        <option value="error">Error</option>
                    </select>
                </div>

                <!-- Start Date and Time -->
                <div class="mb-3">
                    <label for="start_at" class="form-label">Start At</label>
                    <input type="datetime-local" wire:model="start_at" class="form-control" id="start_at" name="start_at">
                </div>

                <!-- End Date and Time -->
                <div class="mb-3">
                    <label for="end_at" class="form-label">End At</label>
                    <input type="datetime-local" wire:model="end_at" class="form-control" id="end_at" name="end_at">
                </div>

                <!-- Active Status -->
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" checked>
                    <label class="form-check-label" wire:model="is_active" for="is_active">Is Active</label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary">Create Announcement</button>
            </form>
        </div>
    </div>
</div>
