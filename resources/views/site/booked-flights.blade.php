@extends('layouts.master')

@section('title')
    Booked Flights
@endsection

@section('content')
    <span class="border border-light" style="background-color:#F0F0F0">
    <div class="container">
        &nbsp;
        <h2>All Booked Flights</h2>
        &nbsp;
    </div>
</span>
    <br>

    <div class="container">
        <a class="btn btn-info" href="/download-booked-flights">Download CSV of Booked Flights</a>
        <br><br>

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
                <th><center>Actions</center></th>
                </thead>
                <tbody>
                @foreach($flights_dep as $f)
                    <tr>
                        <td><center>{{ $f->callsign }}</center></td>
                        <td><center>{{ $f->departure }}</center></td>
                        <td><center>{{ $f->arrival }}</center></td>
                        <td><center>{{ $f->dep_time_formatted }}</center></td>
                        <td><center>{{ $f->arr_time_formatted }}</center></td>
                        <td>
                            <div class="form-group">
                                <center>
                                    <a href="/booking/manage/{{ $f->id  }}" class="btn btn-primary simple-tooltip" data-toggle="tooltip" title="Manage"><i class="fas fa-pencil-alt"></i></a>
                                </center>
                            </div>
                        </td>
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
                <th><center>Actions</center></th>
                </thead>
                <tbody>
                @foreach($flights_arr as $f)
                    <tr>
                        <td><center>{{ $f->callsign }}</center></td>
                        <td><center>{{ $f->departure }}</center></td>
                        <td><center>{{ $f->arrival }}</center></td>
                        <td><center>{{ $f->dep_time_formatted }}</center></td>
                        <td><center>{{ $f->arr_time_formatted }}</center></td>
                        <td>
                            <div class="form-group">
                                <center>
                                    <a href="/booking/manage/{{ $f->id  }}" class="btn btn-primary simple-tooltip" data-toggle="tooltip" title="Manage"><i class="fas fa-pencil-alt"></i></a>
                                </center>
                            </div>
                        </td>
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
