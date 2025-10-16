@extends('layout.popups')
@section('content')
<script>
    var existingProducts = [];
    @foreach ($sale->details as $product)
        @php
            $productID = $product->productID;
        @endphp
        existingProducts.push({{$productID}});
    @endforeach
</script>
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card" id="demo">
                <div class="row">
                    <div class="col-12">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-6"><h3> Edit Sale </h3></div>
                                <div class="col-6 d-flex flex-row-reverse">
                                    <a href="{{ route('sale.index') }}" class="btn btn-danger">Close</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!--end row-->
                <div class="card-body">

                        <div class="row">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="product">Product</label>
                                        <select name="product" class="selectize" id="product">
                                            <option value=""></option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                               
                            </div>
                        </div>
                        <div class="row">
                            <form action="{{ route('sale.update', $sale->id) }}" method="post">
                                @csrf
                                @method("PUT")
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-12">
                                            <table class="table table-striped table-hover">
                                                <thead>
                                                    <th width="30%">Item</th>
                                                    <th class="text-center">Price</th>
                                                    <th class="text-center">Qty</th>
                                                    <th class="text-end">Amount</th>
                                                    <th></th>
                                                </thead>
                                                <tbody id="products_list">
                                                    @foreach ($sale->details as $product)
                                                    @php
                                                        $id = $product->product->id;
                                                    @endphp
                                                    <tr id="row_{{$id}}">
                                                        <td class="no-padding">{{$product->product->name}}</td>
                                                       <td class="no-padding"><input type="number" name="price[]" required step="any" value="{{$product->price}}" min="0" class="form-control text-center no-padding" id="price_{{$id}}"></td>
                                                        <td class="no-padding"><input type="number" name="qty[]" oninput="updateChanges({{$id}})" min="0" required step="any" value="{{$product->qty}}" class="form-control text-center no-padding" id="qty_{{$id}}"></td>
                                                        <td class="no-padding"><input type="number" name="amount[]" min="0.1" readonly required step="any" value="{{$product->amount}}" class="form-control text-center no-padding" id="amount_{{$id}}"></td>
                                                        <td class="no-padding"> <span class="btn btn-sm btn-danger" onclick="deleteRow({{$id}})">X</span> </td>
                                                        <input type="hidden" name="id[]" value="{{$id}}">
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th colspan="2" class="text-end">Total</th>
                                                        <th class="text-center" id="totalQty">0.00</th>
                                                        <th class="text-end" id="totalAmount">0.00</th>
                                                        <th></th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="row">
                                                    <div class="col-3 mt-2">
                                                        <div class="form-group">
                                                            <label for="date">Date</label>
                                                            <input type="date" name="date" id="date" value="{{ $sale->date }}" class="form-control">
                                                        </div>
                                                    </div>
                                                     <div class="col-3 mt-2">
                                                        <div class="form-group">
                                                            <label for="customer">Customer</label>
                                                            <select name="customerID" id="customerID"  class="selectize1">
                                                                @foreach ($customers as $customer)
                                                                    <option value="{{ $customer->id }}" @selected($customer->id == $sale->customer_id)>{{ $customer->title }} - {{ $customer->category }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="form-group customerName mt-2">
                                                            <label for="customerName">Name</label>
                                                            <input type="text" name="customerName" id="customerName" value="{{ $sale->customer->name }}" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-3 mt-2">
                                                        <div class="form-group">
                                                            <label for="status">Payment Status</label>
                                                            <select name="status" id="status1" class="form-control">
                                                                <option value="paid" @selected($sale->payment_status == 'paid')>Paid</option>
                                                                <option value="pending" @selected($sale->payment_status == 'pending')>Pending</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                      <div class="col-3 mt-2">
                                                        <div class="form-group">
                                                            <label for="account">Account</label>
                                                            <select name="accountID" id="account" class="selectize1">
                                                                @foreach ($accounts as $account)
                                                                    <option value="{{ $account->id }}">{{ $account->title }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 mt-2">
                                                        <div class="form-group">
                                                            <label for="notes">Notes</label>
                                                            <textarea name="notes" id="notes" class="form-control" cols="30" rows="5">{{$sale->notes}}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 mt-2">
                                                        <button type="submit" class="btn btn-primary w-100">Update Sale</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                            </div>

                        </form>
                </div>

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
        $(".selectize1").selectize();
        $(".selectize").selectize({
            onChange: function(value) {
                if (!value.length) return;
                if (value != null) {
                    getSingleProduct(value);
                    this.clear();
                    this.focus();
                }

            },
        });

        function getSingleProduct(id) {
            $.ajax({
                url: "{{ url('sales/getproduct/') }}/" + id,
                method: "GET",
                success: function(product) {
                    let found = $.grep(existingProducts, function(element) {
                        return element === product.id;
                    });
                    if (found.length > 0) {

                    } else {
                        var id = product.id;
                        var html = '<tr id="row_' + id + '">';
                        html += '<td class="no-padding">' + product.name + '</td>';
                        html += '<td class="no-padding"><input type="number" name="price[]" required step="any" value="'+product.price+'" min="0" class="form-control text-center no-padding" id="price_' + id + '"></td>';
                        html += '<td class="no-padding"><input type="number" name="qty[]" oninput="updateChanges(' + id + ')" min="0" required step="any" value="1" class="form-control text-center no-padding" id="qty_' + id + '"></td>';
                        html += '<td class="no-padding"><input type="number" name="amount[]" min="0.1" readonly required step="any" value="1" class="form-control text-center no-padding" id="amount_' + id + '"></td>';
                        html += '<td class="no-padding"> <span class="btn btn-sm btn-danger" onclick="deleteRow('+id+')">X</span> </td>';
                        html += '<input type="hidden" name="id[]" value="' + id + '">';
                        html += '</tr>';
                        $("#products_list").prepend(html);
                        existingProducts.push(id);
                        updateChanges(id);
                    }
                }
            });
        }

        function updateChanges(id) {
            var qty = parseFloat($('#qty_' + id).val());
            var price = parseFloat($('#price_' + id).val());

            var amount = qty * price;
            $("#amount_"+id).val(amount.toFixed(2));
            updateTotal();
        }
        updateTotal();

        function updateTotal() {
            var total = 0;
            $("input[id^='amount_']").each(function() {
                var inputId = $(this).attr('id');
                var inputValue = $(this).val();
                total += parseFloat(inputValue);
            });

            $("#totalAmount").html(total.toFixed(2));
            var count = $("[id^='row_']").length;
            var numQty = 0;
            $("input[id^='qty_']").each(function() {
                var value = parseFloat($(this).val());
                var unit = $("")
                if (!isNaN(value)) {
                    numQty += value ;
                }
            });
            $("#totalQty").html(count + "(" + numQty + ")");
        }

        function deleteRow(id) {
            existingProducts = $.grep(existingProducts, function(value) {
                return value !== id;
            });
            $('#row_'+id).remove();
            updateTotal();
        }

        function checkAccount()
    {
        var id = $("#customerID").find(":selected").val();
        console.log(id);
        if(id == 3)
        {
            $(".customerName").removeClass("d-none");
            $('#status1 option').each(function() {
            var optionValue = $(this).val();
            if (optionValue === 'advanced' || optionValue === 'pending' || optionValue === 'partial') {
                $(this).prop('disabled', true);
            }
            if (optionValue === 'paid') {
                $(this).prop('selected', true);
            }
            });
        }
        else
        {
            $(".customerName").addClass("d-none");
            $('#status1 option').each(function() {
            var optionValue = $(this).val();
            if (optionValue === 'advanced' || optionValue === 'pending' || optionValue === 'partial') {
                $(this).prop('disabled', false);
            }
            });
        }
    }
   

    $("#customerID").on("change", function(){
        checkAccount();
    });
    checkAccount();

    </script>
@endsection
