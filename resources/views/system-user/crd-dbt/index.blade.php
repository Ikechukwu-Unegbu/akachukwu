@extends('layouts.admin.app')

@section('header')
    <!-- Your header content goes here -->
@endsection



<div class="container mx-auto row" style="margin-top:1rem;">
     <form calss="col-lg-12">
            <!-- Amount Input Field -->
            <div class="mb-3">
                <label for="amountInput" class="form-label">Amount</label>
                <input type="text" class="form-control" id="amountInput" placeholder="Enter amount">
            </div>

            <!-- Reason Textarea -->
            <div class="mb-3">
                <label for="reasonTextarea" class="form-label">Reason</label>
                <textarea class="form-control" id="reasonTextarea" rows="3" placeholder="Enter reason"></textarea>
            </div>

            <!-- Username Input Field -->
            <div class="mb-3">
                <label for="usernameInput" class="form-label">Username</label>
                <input type="text" class="form-control" id="usernameInput" placeholder="Enter username">
            </div>

            <!-- Dropdown -->
            <div class="mb-3">
                <label for="dropdownSelect" class="form-label">Select</label>
                <select class="form-select" id="dropdownSelect">
                    <option selected>Select one...</option>
                    @if(auth()->user()->can())

                    @endcan
                    <option value="option2">Option 2</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
</div>

