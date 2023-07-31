@extends('layouts.dashboard')

@section('title', 'Circuits - ACCT # '.$BTNAccount->getAttribute('AccountNum'))

@section('content')
    <div class="main-container">
        <!-- start container -->
        <div class="container">
            <!-- start confirmation -->
            <div class="confirmation">

                <a href="{{ route('dashboard.inventory.index') }}" class="nav_button">&lt; Back </a>

                <h3 class="ml-3">ACCT # {{ $BTNAccount->getAttribute('AccountNum') }}</h3>

                @include('dashboard.inventory.partials.links', ['active' => 'circuits'])

                <div class="clearfix"></div>

                <div class="col-sm-8 col-md-5 col-lg-4 float-xs-right mt-3">
                    <form action="{{ route('dashboard.inventory.circuits.index', [$BTNAccount]) }}" class="simple-search">
                        <input name="search" type="text" value="{{old('search', request('search'))}}" class="form-control search-field" placeholder="Search Circuits">
                        <button class="search-btn"><i class="fa fa-search"></i></button>
                    </form>
                </div>

                <div class="tab-content mt-3">
                    <div class="tab-pane active">
                        @include('dashboard.inventory.index.partials._circuits', ['loc' => 'Circuit'])
                    </div>
                </div>
            </div>
            <!-- end confirmation -->
        </div>
        <!-- end container -->
    </div>
    <!-- end main-container -->
    @can('edit')
        <div class="bottom-block">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-xs-12 text-center">
                        <a class="btn-primary" href="{{ route('dashboard.inventory.circuits.create', [$BTNAccount, 'page' => $page, 'search' => $search, 'category' => \App\Models\Category::VOICE]) }}">
                            NEW CIRCUIT
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endcan
    <!-- end main-container -->
@endsection
