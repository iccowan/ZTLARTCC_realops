@extends('layouts.master')

@section('title')
    Edit Flight
@endsection

@section('content')
    <span class="border border-light" style="background-color:#F0F0F0">
    <div class="container">
        &nbsp;
        <h2>Edit Flight</h2>
        &nbsp;
    </div>
</span>
    <br>

    <div class="container">
        <form method="POST" action="/edit-flight/{{ $flight->id }}/save" accept-charset="UTF-8">
            @csrf
            <div class="form-group">
                <label class="label">Callsign</label>
                <input type="text" class="form-control" disabled  value="{{ $flight->callsign }}">
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="label">Departure Airport</label>
                        <input type="text" name="departure" class="form-control" value="{{ $flight->departure }}" placeholder="Departure Airport">
                    </div>
                    <div class="form-group">
                        <label class="label">Departure Time (Zulu)</label>
                        <div class="input-group date" id="datetimepicker1" data-target-input="nearest">
                            <input type="text" name="dep_time" class="form-control datetimepicker-input" value="{{ $flight->dep_time_edit }}" data-target="#datetimepicker1">
                            <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="label">Arrival Airport</label>
                        <input type="text" name="arrival" class="form-control" value="{{ $flight->arrival }}" placeholder="Arrival Airport">
                    </div>
                    <div class="form-group">
                        <label class="label">Arrival Time (Zulu)</label>
                        <div class="input-group date" id="datetimepicker2" data-target-input="nearest">
                            <input type="text" name="arr_time" class="form-control datetimepicker-input" value="{{ $flight->arr_time_edit }}" data-target="#datetimepicker2">
                            <div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="label">Recommended Flight Plan</label>
                <textarea class="form-control" name="flt_plan" placeholder="Flight Plan" rows="10">{{ $flight->flight_plan }}</textarea>
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
