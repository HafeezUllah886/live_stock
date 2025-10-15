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
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="date">Date</label>
                                    <input type="date" name="date" value="{{ date('Y-m-d') }}" id="date"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-md-3 mt-2 mt-md-0">
                                <div class="form-group">
                                    <label for="factory_id">Factory</label>
                                    <select name="factory_id" id="factory_id" class="form-control">
                                        @foreach ($factories as $factory)
                                            <option value="{{ $factory->id }}">{{ $factory->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 mt-2 mt-md-0">
                                <div class="form-group">
                                    <label for="customer_id">Customer</label>
                                    <select name="customer_id" id="customer_id" class="form-control">
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}">{{ $customer->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 mt-2 mt-md-0">
                                <div class="form-group">
                                    <label for="product_id">Product</label>
                                    <select name="product_id" id="product_id" class="form-control">
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <h5>Slaughter Details</h5>
                        <div class="row mb-3">
                            <div class="col-md-3 mt-2 mt-md-0">
                               <div class="row">
                                 <div class="col-6 form-group">
                                    <label for="qty">Live Qty</label>
                                    <input type="number" name="qty" oninput="calculateSlaughterAmount()" id="qty" class="form-control">
                                </div>
                                <div class="col-6 form-group">
                                    <label for="weight">Meat Weight</label>
                                    <input type="number" name="weight" step="any" oninput="calculateTotal()" id="weight" class="form-control">
                                </div>
                               </div>
                            </div>
                            <div class="col-md-3 mt-2 mt-md-0">
                                <div class="form-group">
                                    <label for="price">Price per Kg</label>
                                    <input type="number" name="price" id="price" oninput="calculateTotal()" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-3 mt-2 mt-md-0">
                                <div class="form-group">
                                    <label for="total">Total</label>
                                    <input type="number" name="total" disabled id="total" class="form-control">
                                </div>
                            </div>
                             <div class="col-md-3 mt-2 mt-md-0">
                                <label for="slaughter_charges">Slaughtering Charges</label>
                                <div class="input-group">
                                <input type="number" name="slaughter_charges" id="slaughter_charges" oninput="calculateSlaughterAmount()" class="form-control">
                                <input type="number" name="slaughter_amount" disabled id="slaughter_amount" class="form-control">
                                </div>
                            </div>
                        </div>
                        <h5>Rejected Details</h5>
                        <div class="row mb-3">
                            <div class="col-md-3 mt-2 mt-md-0">
                                <div class="form-group">
                                    <label for="rejected_weight">Weight</label>
                                    <input type="number" name="rejected_weight" oninput="calculateRejectedTotal()" step="any" id="rejected_weight"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="col-md-3 mt-2 mt-md-0">
                                <div class="form-group">
                                    <label for="rejected_price">Price per Kg</label>
                                    <input type="number" name="rejected_price" oninput="calculateRejectedTotal()" id="rejected_price" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-3 mt-2 mt-md-0">
                                <div class="form-group">
                                    <label for="rejected_total">Total</label>
                                    <input type="number" name="rejected_total" disabled id="rejected_total" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-3 mt-2 mt-md-0">
                                <div class="form-group">
                                    <label for="butcher">Butcher</label>
                                    <select name="butcher_id" id="butcher_id" class="form-control">
                                        @foreach ($butchers as $butcher)
                                            <option value="{{ $butcher->id }}">{{ $butcher->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <h5>Ober Details</h5>
                        <div class="row mb-3">
                            <div class="col-md-3 mt-2 mt-md-0">
                                <div class="form-group">
                                    <label for="ober_qty">Qty</label>
                                    <input type="number" name="ober_qty" oninput="calculateOberTotal()" id="ober_qty" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-3 mt-2 mt-md-0">
                                <div class="form-group">
                                    <label for="ober_price">Price per Kg</label>
                                    <input type="number" name="ober_price" oninput="calculateOberTotal()" id="ober_price" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-3 mt-2 mt-md-0">
                                <div class="form-group">
                                    <label for="ober_total">Total</label>
                                    <input type="number" name="ober_total" disabled id="ober_total" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-3 mt-2 mt-md-0">
                                <div class="form-group">
                                    <label for="ober_customer">Buyer</label>
                                    <select name="ober_customer_id" id="ober_customer_id" class="form-control">
                                        @foreach ($butchers as $butcher)
                                            <option value="{{ $butcher->id }}">{{ $butcher->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-10">
                                <div class="form-group">
                                    <label for="notes">Notes</label>
                                    <textarea name="notes" id="notes" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="grand_total">Grand Total</label>
                                    <input type="number" name="grand_total" disabled id="grand_total" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mt-2">
                                <button type="submit" class="btn btn-primary w-100">Save</button>
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

        function calculateSlaughterAmount() {
            var qty = $('#qty').val();
            var slaughter_charges = $('#slaughter_charges').val();
            var total = qty * slaughter_charges;
            $('#slaughter_amount').val(total);
            $('#ober_qty').val(qty);
            calculateGrand();
        }

        function calculateTotal() {
            var weight = $('#weight').val();
            var price = $('#price').val();
            var total = weight * price;
            $('#total').val(total);
            calculateGrand();
        }

        function calculateRejectedTotal() {
            var weight = $('#rejected_weight').val();
            var price = $('#rejected_price').val();
            var total = weight * price;
            $('#rejected_total').val(total);
            calculateGrand();
        }

        function calculateOberTotal() {
            var qty = $('#ober_qty').val();
            var price = $('#ober_price').val();
            var total = qty * price;
            $('#ober_total').val(total);
            calculateGrand();
        }

        function calculateGrand()
        {
            var slaughter_amount = parseFloat($('#slaughter_amount').val() || 0);
            var total = parseFloat($('#total').val() || 0);
            var rejected_total = parseFloat($('#rejected_total').val() || 0);
            var ober_total = parseFloat($('#ober_total').val() || 0);
            var grand_total = slaughter_amount + total + rejected_total + ober_total;
            $('#grand_total').val(grand_total);
        }
    </script>
@endsection
