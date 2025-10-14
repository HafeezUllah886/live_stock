@extends('layout.app')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3>Create Account</h3>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('account.store') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 ">
                                <div class="form-group">
                                    <label for="title">Account Title</label>
                                    <input type="text" name="title" id="title" value="{{ old('title') }}"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category">Category</label>
                                    <select name="category" id="category" class="form-control" onchange="checkCat()">
                                        <option value="Business">Business</option>
                                        <option value="Customer">Customer</option>
                                        <option value="Vendor">Vendor</option>
                                        <option value="Factory">Factory</option>
                                        <option value="Transporter">Transporter</option>
                                        <option value="Butcher">Butcher</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mt-2" id="catBox">
                                <div class="form-group">
                                    <label for="type">Type</label>
                                    <select name="type" id="type" class="form-control">
                                        <option value="Bank">Bank</option>
                                        <option value="Cash">Cash</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mt-2 customer vendor factory transport">
                                <div class="form-group ">
                                    <label for="address">Address</label>
                                    <input type="text" name="address" id="address" value="{{ old('address') }}"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6 mt-2 customer vendor factory transport">
                                <div class="form-group">
                                    <label for="contact">Contact #</label>
                                    <input type="text" name="contact" id="contact" value="{{ old('contact') }}"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-12 mt-3">
                                <button type="submit" class="btn btn-secondary w-100">Create</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- Default Modals -->
@endsection

@section('page-css')
    <link rel="stylesheet" href="{{ asset('assets/libs/selectize/selectize.min.css') }}">
@endsection
@section('page-js')
    <script src="{{ asset('assets/libs/selectize/selectize.min.js') }}"></script>
    <script>
        $(".customer").hide();
        $(".selectize").selectize();

        function checkCat() {
            var cat = $("#category").find(":selected").val();
            if (cat === "Business") {
                $("#catBox").show();
            } else {
                $("#catBox").hide();
            }
            if (cat === "Customer") {
                $(".customer").show();
            } else {
                $(".customer").hide();
            }
            if (cat === "Vendor") {
                $(".vendor").show();
            }
            if (cat === "Factory") {
                $(".factory").show();
            }
            if (cat === "Transport") {
                $(".transport").show();
            }
            /*  else
             {
                 $(".vendor").hide();
                 if(type === "Customer")
                 {
                     $(".customer").show();
                 }
             } */
        }
        checkCat();
    </script>
@endsection
