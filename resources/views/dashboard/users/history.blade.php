@extends('layouts.dashboard')

@section('title', 'Login History - '.$name)

@section('content')
    <div class="main-container" style="padding-bottom:0">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <a href="{{route('dashboard.users.index', [
                                'ListBy' => request('ListBy'),
                                'page' => request('page'),
                                'UsersSearch' => request('UsersSearch')
                            ])}}" class="nav_button">&lt; Back</a>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-12">
                    <h2 class="common-heading" style="border-bottom:0; font-size:1.5em;">Login History: {{$name}}</h2>
                </div>
            </div>
            <div class="row" style="margin-top:-15px;">
                @if(count($history))
                    <div class="col-md-12 ">
                        <div class="table-responsive">
                            <table class="carrier_table table table-striped table-sm text-xs-center">
                                <thead>
                                <tr>
                                    <th>Sign-in Date</th>
                                    <th>Length</th>
                                    <th>IP Address</th>
                                    <th>Location</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($history as $c)
                                        <tr>
                                            <td>{{DateTime::createFromFormat('Y-m-d H:i:s.u', $c['Sessions_BeginDate'])->format('m/d/Y')}}</td>
                                            <td>{{ date_diff(DateTime::createFromFormat('Y-m-d H:i:s.u', $c['Sessions_BeginDate']),DateTime::createFromFormat('Y-m-d H:i:s.u', $c['Sessions_EndDate']))->format('%h:%I') }}</td>
                                            <td>{{$c['ip_address']}}</td>
                                            <td>{{$UsersService->getLocation($c['ip_address'])}}</td>
                                        </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="pagination-links">
                            {!! $history->links() !!}
                        </div>
                    </div>
            </div>
            <br>
            @else
                <h1 style="padding-left:15px;padding-top:45px;">This user does not have any login history.</h1>
            @endif
            </div>
        </div>
        <!-- end container -->
    </div>
@endsection
