@extends('layout.app')
@section('content')
    <div class="row d-flex justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3>View Balance Sheet Report</h3>
                </div>
                <div class="card-body">
                    
                    <div class="form-group mt-2">
                        <label for="category">Account Category</label>
                        <select name="category" id="category" class="form-control">
                            <option value="All">All</option>
                            <option value="Business">Business</option>
                            <option value="Customer">Customer</option>
                            <option value="Vendor">Vendor</option>
                            <option value="Factory">Factory</option>
                            <option value="Transporter">Transporter</option>
                            <option value="Butcher">Butcher</option>
                        </select>
                    </div>
                   
                    </div>
                    <div class="form-group mt-2">
                        <button class="btn btn-success w-100" id="viewBtn">View Report</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
@section('page-js')

    <script>
        $("#viewBtn").on("click", function (){
          
            var category = $("#category").find(':selected').val();
            var url = "{{ route('reportBalanceSheetData', ['category' => ':category']) }}"
        .replace(':category', category);
            window.open(url, "_blank", "width=1000,height=800");
        });
    </script>
@endsection
