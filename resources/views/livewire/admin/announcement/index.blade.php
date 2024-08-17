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
                        <th>Starts</th>
                        <th>Ends</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Loop through announcements -->
                    <!-- Example static rows; replace with dynamic content -->
                    @foreach($announcements as $announcement)
                    <tr>
                        <td>1</td>
                        <td>{{Str::words($announcement->title, 3)}}</td>
                        <td>{{Str::words($announcement->message, 5)}}</td>
                        <td><span class="badge bg-info">{{$announcement->type}}</span></td>
                        <td>{{$announcement->starts_at}}</td>
                        <td>{{$announcement->ends_at}}</td>
                        <td><span class="badge bg-success">Active</span></td>
                        <td style="display: flex; gap:0.2rem;">
                        <a href="/announcements/2/edit" class="btn btn-warning btn-sm"><i class="fa-solid fa-pen-to-square"></i></a>
                        <button class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></button>
                        </td>
                    </tr>
                    @endforeach 
                  
                    <!-- End loop -->
                </tbody>
            </table>
            <div>
                {{$announcements->links()}}
            </div>
        </div>
    </div>
</div>
