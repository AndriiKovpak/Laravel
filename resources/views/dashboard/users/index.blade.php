@extends('layouts.dashboard')

@section('title', 'Users')

@section('content')
    <div class="main-container" style="padding-bottom:0">
        <!-- start container -->
        <div class="container">
            <div class="col-12">
                <div class="row">
                    <div class="col-6 col-lg-3 UACE_btn  @if($ListBy ==1 || $ListBy == null){{'user_active'}}@endif">
                        <a href='{{route('dashboard.users.index', ['ListBy' => '1'])}}' class="UACE_btn">
                            <h1 class="carrier_header" style="padding-top:15px; padding-bottom:15px;"><i class="fa fa-users" aria-hidden="true"></i> USACE ({{$adminUsers}})</h1>
                        </a>
                    </div>
                    <div class="col-6 col-lg-3 REPORTING_btn  @if($ListBy == 2){{'user_active'}}@endif">
                        <a  href='{{route('dashboard.users.index', ['ListBy' => '2'])}}' class="UACE_btn">
                            <h1 class="carrier_header" style="padding-top:15px; padding-bottom:15px;"><i class="fa fa-users" aria-hidden="true"></i> REPORTING ({{$reportingUsers}})</h1>
                        </a>
                    </div>
                    <div class="col-12 col-md-8 col-lg-4 searchCarriers searchUsers">
                        <div class="option-ico second clearfix">
                            <ul>
                                <li class="col-md-12 pr-0" style="padding-left:0px;">
                                    <div class="search col-md-12 pr-0 col-xs-12 float-xs-right" style="padding-left:0px;">
                                        <form id="formUsersSearch">
                                            <input type="hidden" id="show_hide" name="show_hide" value="hide">
                                            <input name="UsersSearch" id="UsersSearch" type="text" value="{{$searchString}}" class="form-control" placeholder="Search Users">
                                            <div class="search-btn"><a id="search_btn_users"><i
                                                            class="fa fa-search"></i></a></div>
                                        </form>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class=" col-lg-2 col-4 addNewCarrierDiv addNewUser">
                        <div class="row">
                            <div class="col-md-1 col-4 hideMob">
                                <a id="show_carrier_list" href="">|</a>
                            </div>
                            <div class="col-md-7 col-lg-10 col-12">
                                <a href="{{route('dashboard.users.create')}}">Add New User</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" style="margin-top:-15px;">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="carrier_table table table-striped table-sm text-xs-center">
                                <thead>
                                <tr>
                                    <th style="width:50%">User Name</th>
                                    <!-- @if(request('ListBy') == '2')
                                    <th style="text-align: center; width: 30%">Districts</th>
                                    @endif -->
                                    @if($searchString)
                                    <th style="text-align: center; width: 30%">Security Group</th>
                                    @endif
                                    <th style="text-align: center">Reset Session</th>
                                    <th style="text-align: center">Login History</th>
                                    <th style="text-align: center">Details</th>
                                    <th style="text-align: center">Edit</th>
                                    <th style="text-align: center">Delete</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($users as $c)
                                    @if(!empty($c['FirstName']))
                                        <tr>
                                            <td>{{$c['FirstName'] . ' ' . $c['LastName']}}</td>
                                            <!-- @if(request('ListBy') == '2')
                                            <td style="text-align: center">
                                            @forelse ($c->DivisionDistricts as $district)
                                                <span>{{ $district->DivisionDistrictCode }}, </span>
                                            @empty
                                                <p>No districts</p>
                                            @endforelse
                                            </td>
                                            @endif -->
                                            <?php
                                                if (! \Session::get($c->SecurityGroup)) {
                                                    $sc = \DB::table('SecurityGroups')->select(['SecurityGroupName'])->where('SecurityGroup', $c->SecurityGroup)->first();
                                                    \Session::put($c->SecurityGroup, $sc);
                                                } else {
                                                    $sc = \Session::get($c->SecurityGroup);
                                                }
                                            ?>
                                            @if($searchString)
                                            <td class="text-center">{{$sc->SecurityGroupName}}</td>
                                            @endif
                                            <td class="text-center">
                                                <a href="{{route('dashboard.users.session', [
                                                        $c['UserID'],
                                                        'index',
                                                        'page' => request('page'),
                                                        'ListBy' => request('ListBy'),
                                                        'UsersSearch' => $searchString
                                                    ])}}">
                                                    <i class="fa fa-repeat fa-ac"></i>
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{route('dashboard.users.history', [
                                                        $c['UserID'],
                                                        'page' => request('page'),
                                                        'ListBy' => request('ListBy'),
                                                        'UsersSearch' => $searchString
                                                    ])}}">
                                                    <i class="fa fa-clock-o fa-ac"></i>
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{route('dashboard.users.show', [
                                                        $c['UserID'],
                                                        'page' => request('page'),
                                                        'ListBy' => request('ListBy'),
                                                        'UsersSearch' => $searchString
                                                    ])}}">
                                                    <i class="fa fa-eye fa-ac"></i>
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{route('dashboard.users.edit', [
                                                        $c['UserID'],
                                                        'page' => request('page'),
                                                        'ListBy' => request('ListBy'),
                                                        'UsersSearch' => $searchString
                                                    ])}}">
                                                    <i class="fa fa-pencil fa-ac"></i>
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                <a>
                                                    <a href="#" data-confirmation-btn="true"><i class="fa fa-trash fa-ac" aria-hidden="true"></i></a>
                                                </a>
                                            </td>
                                            <td data-confirmation-body colspan="7" class="text-left">

                                                <form style="display: none;" method="POST" action="{{ route('dashboard.users.destroy', [$c['UserID']]) }}">
                                                    {!! csrf_field() !!} {!! method_field('DELETE') !!}
                                                    <input type="hidden" name="p" value="{{ request('page') }}" />
                                                </form>

                                                <i class="fa fa-info-circle" aria-hidden="true"></i>
                                                Are you sure you want to delete this user?

                                                <a href="" data-delete-form>Yes</a>
                                                <a href="#" data-confirmation-btn="false">No</a>
                                            </td>
                                        </tr>
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center no-highlight">
                                            <h3 class="not-found text-center">No results displayed</h3>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="pagination-links">
                            {{ $users->appends([
                                'ListBy' => request('ListBy'),
                                'UsersSearch' => $searchString
                                ])->links() }}
                        </div>
                    </div>
            </div>
            <br>
        </div>
        <!-- end container -->
    </div>
@endsection
