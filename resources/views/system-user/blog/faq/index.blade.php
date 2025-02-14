@extends('layouts.admin.app')
@section('content')
<div>
    <x-admin.page-title title="Content Mgt.">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="Content Categories" status="true" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </x-admin.page-title>

    <section class="section">
        <div class="d-flex justify-content-between mb-4">
            <h3>FAQs</h3>
            @can('create faq')
            <a href="{{route('admin.faq.create')}}" class="btn btn-primary">Add New FAQ</a>
            @endcan
        </div>

        @if($faqs->isEmpty())
            <div class="alert alert-warning" role="alert">
                No FAQs available. Please add some FAQs.
            </div>
        @else
            <div class="row">
                @foreach($faqs as $faq)
                    <div class="col-lg-12 col-md-12 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">{{ $faq->title }}</h5>
                                <hr>
                                 
                                <div class="card-text">{!! $faq->excerpt !!}</div>
                                <hr>
                                <div class="card-text">{!! $faq->content !!}</div>
                                <div class="d-flex justify-content-between">
                                    @can('edit faq')
                                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#editFaqModal{{ $faq->id }}">Edit</button>
                                    @endcan
                                    @can('delete faq')
                                    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteFaqModal{{ $faq->id }}">Delete</button>
                                    @endcan
                                </div>
                            </div>
                        </div>

                        @can('edit faq')
                        <!-- Edit Modal -->
                        <div class="modal fade" id="editFaqModal{{ $faq->id }}" tabindex="-1" aria-labelledby="editFaqModalLabel{{ $faq->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editFaqModalLabel{{ $faq->id }}">Edit FAQ</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('admin.faq.update', $faq->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="faqTitle{{ $faq->id }}" class="form-label">FAQ Title</label>
                                                <input type="text" class="form-control" id="faqTitle{{ $faq->id }}" name="title" value="{{ $faq->title }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="faqQuestion{{ $faq->id }}" class="form-label">FAQ Question</label>
                                                <textarea class="form-control" name="excerpt" id="faqQuestion{{ $faq->id }}" rows="3" required>{{ $faq->excerpt }}</textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label for="faqContent{{ $faq->id }}" class="form-label">FAQ Content</label>
                                                <textarea class="form-control" name="content" id="faqContent{{ $faq->id }}" rows="3" required>{{ $faq->content }}</textarea>
                                            </div>

                                            <!-- Status Selection -->
                                            <div class="mb-3">
                                                <label for="faqStatus{{ $faq->id }}" class="form-label">Status</label>
                                                <select class="form-select" id="faqStatus{{ $faq->id }}" name="status" required>
                                                    <option value="draft" {{ $faq->status == 'draft' ? 'selected' : '' }}>Draft</option>
                                                    <option value="published" {{ $faq->status == 'published' ? 'selected' : '' }}>Published</option>
                                                    <option value="archived" {{ $faq->status == 'archived' ? 'selected' : '' }}>Archived</option>
                                                </select>
                                            </div>

                                            <!-- Category Selection -->
                                            <div class="mb-3">
                                                <label for="faqCategories{{ $faq->id }}" class="form-label">Categories</label>
                                                <select class="form-select" id="faqCategories{{ $faq->id }}" name="categories[]" multiple required>
                                                    @foreach($categories as $category)
                                                        <option value="{{ $category->id }}" {{ in_array($category->id, $faq->categories->pluck('id')->toArray()) ? 'selected' : '' }}>
                                                            {{ $category->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <small class="form-text text-muted">Hold down the Ctrl (Windows) or Command (Mac) button to select multiple categories.</small>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Save Changes</button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                        @endcan

                        @can('delete faq')
                        <!-- Delete Modal -->
                        <div class="modal fade" id="deleteFaqModal{{ $faq->id }}" tabindex="-1" aria-labelledby="deleteFaqModalLabel{{ $faq->id }}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteFaqModalLabel{{ $faq->id }}">Delete FAQ</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure you want to delete the FAQ <strong>{{ $faq->title }}</strong>?
                                    </div>
                                    <div class="modal-footer">
                                        <form action="{{ route('admin.faq.destroy', $faq->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endcan
                    </div>
                @endforeach
            </div>

            {{ $faqs->links() }}
        @endif

    </section>
</div>

@include('system-user.blog.components._new_category_modal')

@endsection

@push('title')
    Content / Categories
@endpush
