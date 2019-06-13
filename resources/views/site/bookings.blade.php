@extends('layouts.master')

@section('title')
    Bookings
@endsection

@section('content')
    <span class="border border-light" style="background-color:#F0F0F0">
    <div class="container">
        &nbsp;
        <h2>Bookings</h2>
        &nbsp;
    </div>
</span>
    <br>

    <div class="container">
        @if(Auth::check() && Auth::user()->isStaff())
            <a href="/new-flight" class="btn btn-primary">Add New Flight</a>
            <br><br>
        @endif
        <div class="form-inline" id="sort">
            <select class="form-control" style="width: 280px" id="sorting" name="sorting">
                <option selected>Filter Flights</option>
                <option value="1">Airline - AAL</option>
                <option value="2">Airline - ACA</option>
                <option value="3">Airline - AFR</option>
                <option value="4">Airline - BAW</option>
                <option value="5">Airline - DAL</option>
                <option value="6">Airline - DLH</option>
                <option value="7">Airline - EDV</option>
                <option value="8">Airline - FFT</option>
                <option value="9">Airline - JBU</option>
                <option value="10">Airline - JIA</option>
                <option value="11">Airline - KLM</option>
                <option value="12">Airline - NKS</option>
                <option value="13">Airline - QTR</option>
                <option value="14">Airline - SKW</option>
                <option value="15">Airline - SLI</option>
                <option value="16">Airline - SWA</option>
                <option value="17">Airline - THY</option>
                <option value="18">Airline - UAL</option>
                <option value="19">Airline - VIR</option>
                <option value="20">Length of Flight (hrs)</option>
                <option value="21">Departure Time - 22:00z - 22:30z</option>
                <option value="22">Departure Time - 22:30z - 23:00z</option>
                <option value="23">Departure Time - 23:00z - 23:30z</option>
                <option value="24">Departure Time - 00:00z - 00:30z</option>
                <option value="25">Departure Time - 00:30z - 01:00z</option>
                <option value="26">Departure Time - 01:00z - 01:30z</option>
                <option value="27">Departure Time - 01:30z - 02:00z</option>
                <option value="28">Departure Time - 02:00z - 02:30z</option>
                <option value="29">Departure Time - 02:30z - 03:00z</option>
                <option value="30">Arrival Time - 22:00z - 22:30z</option>
                <option value="31">Arrival Time - 22:30z - 23:00z</option>
                <option value="32">Arrival Time - 23:00z - 23:30z</option>
                <option value="33">Arrival Time - 00:00z - 00:30z</option>
                <option value="34">Arrival Time - 00:30z - 01:00z</option>
                <option value="35">Arrival Time - 01:00z - 01:30z</option>
                <option value="36">Arrival Time - 01:30z - 02:00z</option>
                <option value="37">Arrival Time - 02:00z - 02:30z</option>
                <option value="38">Arrival Time - 02:30z - 03:00z</option>
            </select>
            <div class="hidden" id="hidden">
                &nbsp;&nbsp;&nbsp;&nbsp;
                <input class="text form-control" placeholder="Number of Hours" style="width: 280px" id="search" name="search">
                &nbsp;&nbsp;&nbsp;&nbsp;
                <button class="btn btn-success" id="submit">Submit</button>
            </div>
        </div>
        <br>
        @if($sort != null)
            <a href="/bookings" class="btn btn-info">Remove Filter</a>
            <br><br>
        @endif
        <a name="dep"></a>
        <h3>Departures</h3><a href="#arr"><p>(Jump to Arrivals)</p></a>
        <div class="table">
            <table class="table table-bordered">
                <thead>
                <th><center>Callsign</center</th>
                <th><center>Departure Airport</center></th>
                <th><center>Arrival Airport</center></th>
                <th><center>Departure Time (Zulu)</center></th>
                <th><center>Arrival Time (Zulu)</center></th>
                <th><center>Book Now</center></th>
                @if(Auth::check() && Auth::user()->isStaff())
                    <th>Actions</th>
                @endif
                </thead>
                <tbody>
                @foreach($flights_dep as $f)
                    <tr>
                        <td><center>{{ $f->callsign }}</center></td>
                        <td><center>{{ $f->departure }}</center></td>
                        <td><center>{{ $f->arrival }}</center></td>
                        <td><center>{{ $f->dep_time_formatted }}</center></td>
                        <td><center>{{ $f->arr_time_formatted }}</center></td>
                        @if($f->isBooked())
                            <td><center><a class="btn btn-danger btn-sm disabled">Already Booked</a></center></td>
                        @elseif(Auth::check() && !Auth::user()->canBookAnother($f->id))
                            <td><center><a href="/book/{{ $f->id }}" class="btn btn-success btn-sm disabled">Book Now!</a></center></td>
                        @else
                            <td><center><a href="/book/{{ $f->id }}" class="btn btn-success btn-sm">Book Now!</a></center></td>
                        @endif
                        @if(Auth::check() && Auth::user()->isStaff())
                            <td>
                                <div class="form-group">
                                    <center>
                                        <a href="/booking/manage/{{ $f->id  }}" class="btn btn-primary simple-tooltip" data-toggle="tooltip" title="Manage"><i class="fas fa-pencil-alt"></i></a>
                                    </center>
                                </div>
                            </td>
                        @endif
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <hr>
        <a name="arr"></a>
        <h3>Arrivals</h3><a href="#dep"><p>(Jump to Departures)</p></a>
        <div class="table">
            <table class="table table-bordered">
                <thead>
                <th><center>Callsign</center</th>
                <th><center>Departure Airport</center></th>
                <th><center>Arrival Airport</center></th>
                <th><center>Departure Time (Zulu)</center></th>
                <th><center>Arrival Time (Zulu)</center></th>
                <th><center>Book Now</center></th>
                @if(Auth::check() && Auth::user()->isStaff())
                    <th>Actions</th>
                @endif
                </thead>
                <tbody>
                @foreach($flights_arr as $f)
                    <tr>
                        <td><center>{{ $f->callsign }}</center></td>
                        <td><center>{{ $f->departure }}</center></td>
                        <td><center>{{ $f->arrival }}</center></td>
                        <td><center>{{ $f->dep_time_formatted }}</center></td>
                        <td><center>{{ $f->arr_time_formatted }}</center></td>
                        @if($f->isBooked())
                            <td><center><a class="btn btn-danger btn-sm disabled">Already Booked</a></center></td>
                        @elseif(Auth::check() && !Auth::user()->canBookAnother($f->id))
                            <td><center><a href="/book/{{ $f->id }}" class="btn btn-success btn-sm disabled">Book Now!</a></center></td>
                        @else
                            <td><center><a href="/book/{{ $f->id }}" class="btn btn-success btn-sm">Book Now!</a></center></td>
                        @endif
                        @if(Auth::check() && Auth::user()->isStaff())
                            <td>
                                <div class="form-group">
                                    <center>
                                        <a href="/booking/manage/{{ $f->id  }}" class="btn btn-primary simple-tooltip" data-toggle="tooltip" title="Manage"><i class="fas fa-pencil-alt"></i></a>
                                    </center>
                                </div>
                            </td>
                        @endif
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- JavaScript --}}
    <script>
        // Wait for a click on the sort div
        var e = document.getElementById("sorting");
        e.onclick = updateSearch;

        // Check for clicks on the submit button
        var sub = document.getElementById("submit");
        sub.onclick = searchLength;

        // Updates the sort search query
        function updateSearch() {
            let selection = parseInt(e.options[e.selectedIndex].value);

            // Make sure the number of hours is hidden or not correctly
            if(selection === 20) {
                let hiddenElement = document.getElementById("hidden");
                hiddenElement.classList.remove("hidden");
            } else {
                let hiddenElement = document.getElementById("hidden");
                if(!hiddenElement.classList.contains("hidden"))
                    hiddenElement.classList.add("hidden");
            }

            // Now, return the correct result for all of the other selections
            // Get the current URL to make sure we aren't on the selected page
            let url = window.location.href;
            let qu = url.split("?");

            // Set the default URL if nothing is being selected
            let newUrl = url;
            let oldQu = '?' + qu[1];
            let newQu = oldQu;

            // Check and see which box was selected; set the new query
            if(selection <= 19) {
                // Find the airline being searched
                let airline = e.options[e.selectedIndex].text.substring(10);
                newQu = '?sort=airline&airline=' + airline;
            } else if(selection >= 21 && selection <= 29) {
                // Find the time being searched
                let time = e.options[e.selectedIndex].text.substring(17);
                let start = time.substr(0, 5);
                let end = time.substr(9, 5);
                newQu = '?sort=dep_time&start_time=' + start + '&end_time=' + end;
            } else if(selection >= 30) {
                // Find the time being searched
                let time = e.options[e.selectedIndex].text.substring(15);
                let start = time.substr(0, 5);
                let end = time.substr(9, 5);
                newQu = '?sort=arr_time&start_time=' + start + '&end_time=' + end;
            }

            // Make sure the old query isn't the same
            if(newQu !== oldQu) {
                newUrl = qu[0] + newQu;
            }

            // Set the new URL
            if(newUrl !== url)
                window.location.href = newUrl;
        }

        // Submits the search for length of flight
        function searchLength() {
            // Make sure the time is in integer form
            let lengthInput = document.getElementById("search").value;
            var lengthInt = parseInt(lengthInput);
            lengthInput = lengthInt.toString();

            // Get window URL and query
            let url = window.location.href;
            let qu = url.split("?");

            // Make sure they aren't searching for the same thing. Only continue if different
            let newQu = '?sort=length&time=' + lengthInput;
            if(newQu !== qu[1]) {
                var newUrl = qu[0] + newQu;
            }

            // Finally, set the new url
            window.location.href = newUrl;
        }
    </script>

    {{-- CSS --}}
    <style>
        .hidden {
            display: none;
        }
    </style>
@endsection
