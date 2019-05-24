<div class="container">
    <nav class="navbar navbar-expand-lg navbar-light">
        <a class="navbar-brand" href="/"><img width="100" src="/photos/logo.png"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample10" aria-controls="navbarsExample10" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsExample10">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/bookings">Bookings</a>
                </li>
                @if(Auth::check())
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="dashboard" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i>Welcome,</i> {{ Auth::user()->full_name }}</a>
                        <div class="dropdown-menu" aria-labelledby="dashboard">
                            <a class="dropdown-item" href="/manage-booking">Manage your Booking</a>
                            <a class="dropdown-item" href="/logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
                        </div>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="/login">Login</a>
                    </li>
                @endif
            </ul>
        </div>
    </nav>
</div>
