<div class="container mt-5">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <div class="card">
        <div class="card-header">
            <h4>Announcements List</h4>
            <a href="{{route('admin.announcement')}}">Create Announcement</a>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Message</th>
                        <th>Type</th>
                        <th>Link</th>
                        <th>Starts</th>
                        <th>Ends</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Loop through announcements -->
                    <!-- Example static rows; replace with dynamic content -->
                    @forelse($announcements as $announcement)
                    <tr>
                        <td>{{ $loop->index + 1 }}</td>
                        <td>{{Str::words($announcement->title, 3)}}</td>
                        <td>{{Str::words($announcement->message, 5)}}</td>
                        <td><span class="badge bg-info">{{$announcement->type}}</span></td>
                        <td>
                            @if ($announcement->link)
                            <small><a target="_blank" href="{{ $announcement->link }}">{{ Str::substr($announcement->title, 0, 10) }}</a></small>
                            @else
                            N/A
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($announcement->start_at)->format('d M Y - h:s:i a') }}</td>
                        <td>{{ \Carbon\Carbon::parse($announcement->end_at)->format('d M Y - h:s:i a') }}</td>
                        <td><span class="badge bg-{{ $announcement->is_active ? 'success' : 'danger' }}">{{ $announcement->is_active ? 'Active' : 'Not-Active' }}</span></td>
                        <td style="display: flex; gap:0.2rem;">
                            <a href="{{ route('admin.announcement.edit', $announcement->id) }}" class="btn btn-warning btn-sm">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <button 
                                data-bs-toggle="modal"
                                data-bs-target="#deleteAnnouncement-{{ $announcement->id }}"
                                class="btn btn-danger btn-sm">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                            <div wire:ignore.self class="modal fade" id="deleteAnnouncement-{{ $announcement->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">{{ $announcement->title }}</h5>
                                            <button type="button"  class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form wire:submit="delete({{ $announcement->id }})"">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="text-center">
                                                    <h5>Are you sure?</h5>
                                                    <p>You want to delete: <span class="text-danger">{{ $announcement->title }}</span></p>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Continue</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">No Records Found!</td>
                    </tr>
                    @endforelse
                  
                    <!-- End loop -->
                </tbody>
            </table>
            <div>
                {{$announcements->links()}}
            </div>
        </div>
    </div>
</div>
@push('title')
    Content :: Announcement
@endpush