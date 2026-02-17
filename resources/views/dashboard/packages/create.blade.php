@extends('dashboard.layouts.main')

@section('content')
<div class="container-fluid p-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="text-center mb-4">
                <h2 style="color: #DDB02C;">Create New Package</h2>
            </div>
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white" style="border-bottom: 2px solid #DDB02C;">
                    <h5 class="mb-0" style="color: #DDB02C;">Package Form</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('packages.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Package Name</label>
                            <input type="text" name="name" class="form-control" id="name" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="3" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="departure_date" class="form-label">Departure Date</label>
                            <input type="date" name="departure_date" class="form-control" id="departure_date" required>
                        </div>

                        <div class="mb-3">
                            <label for="price" class="form-label">Price</label>
                            <input type="text" name="formatted_price" class="form-control" id="price" required>
                            <input type="hidden" id="hidden-price" name="price">
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn" style="background-color: #DDB02C; color: white;">Create</button>
                            <a href="{{ route('packages.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
