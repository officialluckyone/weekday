@extends('layouts.backend.main',['subtitle' => 'Dashboard'])

@section('vendorcss')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />
@endsection

@section('content')

<div class="row g-2">
    <div class="col-12">
        <h3>List of Projects</h3>
        <div class="card">
            <div class="card-body">
                <form method="post" id="searchForm">
                    <div class="d-flex gap-2">
                        @csrf
                        <div class="input-group input-group-merge">
                            <span class="input-group-text" id="name-project"><i class="ri-search-line"></i></span>
                            <input
                              type="text"
                              name="name_project"
                              class="form-control"
                              placeholder="Enter Your Project Name"
                              aria-label="Enter Your Project Name"
                              aria-describedby="name-project"
                              autocomplete="off"/>
                        </div>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text" id="name-project"><i class="ri-calendar-line"></i></span>
                            <input
                              type="text"
                              name="date_project"
                              class="form-control datepicker"
                              placeholder="Enter Your Project Date"
                              aria-label="Enter Your Project Date"
                              aria-describedby="date-project"
                              autocomplete="off"/>
                        </div>
                        <button type="submit" class="btn btn-info">Search</button>
                        <button type="reset" class="btn btn-danger" id="btnReset">Clear</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-body" id="chartjs"></div>
        </div>
    </div>
</div>

@endsection

@section('vendorjs')
<script src="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
@endsection

@section('pagejs')
    <script>
        $(document).ready(function(){
            var chart;

            $('.datepicker').datepicker({
                todayHighlight: true,
                format: 'dd-mm-yyyy',
                orientation: isRtl ? 'auto right' : 'auto left'
            })

            function loadChart(data) {
                var options = {
                    chart: { type: 'bar', height: 350 },
                    series: [
                        { name: 'To Do', data: data.map(proj => proj.todo) },
                        { name: 'In Progress', data: data.map(proj => proj.in_progress) },
                        { name: 'Done', data: data.map(proj => proj.done) }
                    ],
                    xaxis: { categories: data.map(proj => proj.name) }
                };

                if (chart) {
                    chart.updateOptions(options);
                } else {
                    chart = new ApexCharts(document.querySelector("#chartjs"), options);
                    chart.render();
                }
            }

            function fetchData() {
                var name = $("input[name='name_project']").val();
                var date = $("input[name='date_project']").val();

                $.ajax({
                    url: "{{ route('dashboard.progress') }}",
                    method: "GET",
                    data: { name: name, date: date },
                    success: function (response) {
                        loadChart(response);
                    }
                });
            }

            $("#searchForm").submit(function (e) {
                e.preventDefault();
                fetchData();
            });

            $("#btnReset").click(function () {
                $("#searchForm").trigger("reset");
                fetchData();
            });

            fetchData();
        })
    </script>
@endsection
