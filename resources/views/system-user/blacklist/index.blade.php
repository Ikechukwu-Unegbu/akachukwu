@extends('layouts.admin.app')
@section('content')
    <div>
        <x-admin.page-title title="Content">
            <x-admin.page-title-item subtitle="Dashboard" link="{{ route('admin.dashboard') }}" />
            <x-admin.page-title-item subtitle="Blacklist" status="true" />
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <style>
                body {
                    font-family: 'Arial', sans-serif;
                    background-color: #f8f9fa;
                }

                /* .sidebar {
                    height: 100vh;
                    background-color: #343a40;
                }

                .sidebar a {
                    color: white;
                    text-decoration: none;
                    padding: 15px;
                    display: block;
                    transition: all 0.3s;
                }

                .sidebar a:hover {
                    background-color: #495057;
                } */

                .main-content {
                    padding: 20px;
                }

                .card-custom {
                    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
                    border-radius: 10px;
                }
            </style>
        </x-admin.page-title>
    
    <div class="card">
        <div class="card-header">
        <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form action="/search" method="GET" class="d-flex">
                    <input 
                        type="text" 
                        name="query" 
                        class="form-control me-2" 
                        placeholder="Search..." 
                        required>
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>
            </div>
        </div>
    </div>
        </div>
        <div class="card-body">
            <x-admin.table>
                 <x-admin.table-header :headers="['ID', 'Type', 'Value', 'Action']" />
                <x.admin.table-body>
                    @foreach($blacklists as $blacklist)
                    <tr>
                        <td>{{$blacklist->id}}</td>
                        <td>{{$blacklist->type}}</td>
                        <td>{{$blacklist->value}}</td>
                        <td>
                            <button>Remove</button>
                        </td>
                    </tr>

                    @endforeach
                </x.admin.table-body>
            </x-admin.table>
        </div>
    </div>
    </div>

  
@endsection
@push('title')
    Settings / Roles & Permissions
@endpush
