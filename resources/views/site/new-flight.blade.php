@extends('layouts.master')

@section('title')
    New flight
@endsection

@section('content')
    <span class="border border-light" style="background-color:#F0F0F0">
    <div class="container">
        &nbsp;
        <h2>New Flight</h2>
        &nbsp;
    </div>
</span>
    <br>

    <div class="container">
        <form method="POST" action="/new-flight/save" accept-charset="UTF-8">
            @csrf
            <div class="form-group">
                <label class="label">Callsign</label>
                <input type="text" name="callsign" class="form-control" placeholder="Callsign">
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="label">Departure Airport</label>
                        <input type="text" name="departure" class="form-control" placeholder="Departure Airport">
                    </div>
                    <div class="form-group">
                        <label class="label">Departure Time (Zulu)</label>
                        <div class="input-group date" id="datetimepicker1" data-target-input="nearest">
                            <input type="text" name="dep_time" class="form-control datetimepicker-input" data-target="#datetimepicker1">
                            <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="label">Arrival Airport</label>
                        <input type="text" name="arrival" class="form-control" placeholder="Arrival Airport">
                    </div>
                    <div class="form-group">
                        <label class="label">Arrival Time (Zulu)</label>
                        <div class="input-group date" id="datetimepicker2" data-target-input="nearest">
                            <input type="text" name="arr_time" class="form-control datetimepicker-input" data-target="#datetimepicker2">
                            <div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="label">Recommended Flight Plan</label>
                <textarea class="form-control" name="flt_plan" placeholder="Flight Plan" rows="10"></textarea>
            </div>

            <button type="submit" class="btn btn-success">Save Flight</button>
            <a href="/bookings" class="btn btn-danger">Cancel</a>
        </form>
    </div>

    <script type="text/javascript">
        $(function () {
            $('#datetimepicker1').datetimepicker({
                format: 'L HH:mm',
                icons: {
                    time: 'fas fa-clock'
                }
            });
        });

        $(function () {
            $('#datetimepicker2').datetimepicker({
                format: 'L HH:mm',
                icons: {
                    time: 'fas fa-clock'
                }
            });
        });
    </script>
@endsection
