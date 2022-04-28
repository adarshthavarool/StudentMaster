
<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand text-bold text-success logo" href="{{asset('/')}}"><H2>StudentMaster</H2></a>
    <div class="container-fluid justify-content-end">
        <nav aria-label="breadcrumb ">
            <ol class="navbar-nav" >
                <li class="nav-item active">
                    <a class="nav-link text-bold" style="color: navy !important;" href="/">Student Listing <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" style="color: navy !important;" href="{{ route("marks.list") }}">Mark Listing <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item active">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                                               onclick="event.preventDefault();
                                        this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </li>
            </ol>
        </nav>
    </div>
</nav>