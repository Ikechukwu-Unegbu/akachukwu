@extends('layouts.admin.app')
@section('content')
<div>
    <x-admin.page-title title="Settings">
        <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
        <x-admin.page-title-item subtitle="Content Categories" status="true" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </x-admin.page-title>

    <section class="section">
        <div class="d-flex justify-content-between mb-4">
            <h3>FAQs</h3>
            <button data-bs-toggle="modal" data-bs-target="#newFaqModal" class="btn btn-primary">Add New FAQ</button>
        </div>

        @if($faqs->isEmpty())
            <div class="alert alert-warning" role="alert">
                No FAQs available. Please add some FAQs.
            </div>
        @else
            <div class="row">
                @foreach($faqs as $faq)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">{{ $faq->title }}</h5>
                                <p class="card-text">{{ $faq->content }}</p>
                                <div class="d-flex justify-content-between">
                                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#editFaqModal{{ $faq->id }}">Edit</button>
                                    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteFaqModal{{ $faq->id }}">Delete</button>
                                </div>
                            </div>
                        </div>

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
                                                <label for="faqContent{{ $faq->id }}" class="form-label">FAQ Content</label>
                                                <textarea class="form-control" name="content" id="faqContent{{ $faq->id }}" rows="3" required>{{ $faq->content }}</textarea>
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
                    </div>
                @endforeach
            </div>

            {{ $faqs->links() }}
        @endif

        <!-- New FAQ Modal -->
        <div class="modal fade" id="newFaqModal" tabindex="-1" aria-labelledby="newFaqModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="newFaqModalLabel">Add New FAQ</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('admin.faq.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="faqTitle" class="form-label">FAQ Title</label>
                                <input type="text" class="form-control" id="faqTitle" name="title" required>
                            </div>
                            <div class="mb-3">
                                <label for="faqContent" class="form-label">FAQ Content</label>
                                <textarea class="form-control" name="content" id="faqContent" rows="3" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Add FAQ</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

@include('system-user.blog.components._new_category_modal')

@endsection

@push('title')
    Content / Categories
@endpush
