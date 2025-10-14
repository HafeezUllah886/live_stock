@extends('layout.popups')
@section('content')
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card" id="demo">
                <div class="row">
                    <div class="col-12">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-6">
                                    <h3> Create Slaughtering </h3>
                                </div>
                                <div class="col-6 d-flex flex-row-reverse">
                                    <a href="{{ route('slaughter.index') }}" class="btn btn-danger">Close</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!--end row-->
                <div class="card-body">
                    <form action="{{ route('slaughter.store') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="date">Date</label>
                                    <input type="date" name="date" value="{{ date('Y-m-d') }}" id="date" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="factory_id">Factory</label>
                                    <select name="factory_id" id="factory_id" class="form-control">
                                        @foreach ($factories as $factory)
                                            <option value="{{ $factory->id }}">{{ $factory->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="customer_id">Customer</label>
                                    <select name="customer_id" id="customer_id" class="form-control">
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}">{{ $customer->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="product_id">Product</label>
                                    <select name="product_id" id="product_id" class="form-control">
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 mt-2">
                                <div class="form-group">
                                    <label for="qty">Qty</label>
                                    <input type="number" name="qty" id="qty" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-3 mt-2">
                                <div class="form-group">
                                    <label for="weight">Weight</label>
                                    <input type="number" name="weight" step="any" id="weight" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-3 mt-2">
                                <div class="form-group">
                                    <label for="price">Price</label>
                                    <input type="number" name="price" id="price" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-3 mt-2">
                                <div class="form-group">
                                    <label for="total">Total</label>
                                    <input type="number" name="total" id="total" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-3 mt-2">
                                <div class="form-group">
                                    <label for="rejected_weight">Rejected Weight</label>
                                    <input type="number" name="rejected_weight" step="any" id="rejected_weight" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-3 mt-2">
                                <div class="form-group">
                                    <label for="rejected_price">Rejected Price</label>
                                    <input type="number" name="rejected_price" id="rejected_price" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-3 mt-2">
                                <div class="form-group">
                                    <label for="rejected_total">Rejected Total</label>
                                    <input type="number" name="rejected_total" id="rejected_total" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-3 mt-2">
                                <div class="form-group">
                                    <label for="butcher">Butcher</label>
                                    <select name="butcher_id" id="butcher_id" class="form-control">
                                        @foreach ($butchers as $butcher)
                                            <option value="{{ $butcher->id }}">{{ $butcher->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 mt-2">
                                <div class="form-group">
                                    <label for="ober_price">Ober Price</label>
                                    <input type="number" name="ober_price" id="ober_price" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-3 mt-2">
                                <div class="form-group">
                                    <label for="ober_total">Ober Total</label>
                                    <input type="number" name="ober_total" id="ober_total" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-3 mt-2">
                                <div class="form-group">
                                    <label for="ober_customer">Ober Buyer</label>
                                    <select name="ober_customer_id" id="ober_customer_id" class="form-control">
                                         @foreach ($butchers as $butcher)
                                            <option value="{{ $butcher->id }}">{{ $butcher->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                        </div>
                    </form>
                </div>
            </div>
            <!--end card-->
        </div>
        <!--end col-->
    </div>
    <!--end row-->
@endsection

@section('page-css')
    <link rel="stylesheet" href="{{ asset('assets/libs/selectize/selectize.min.css') }}">
    <style>
        .no-padding {
            padding: 5px 5px !important;
        }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('page-js')
    <script src="{{ asset('assets/libs/selectize/selectize.min.js') }}"></script>
    <script>
        $(".selectize").selectize();
        
    </script>
@endsection
