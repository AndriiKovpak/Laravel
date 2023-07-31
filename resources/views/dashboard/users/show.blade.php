@extends('layouts.dashboard')

@section('title', 'User Information - '. $user->FullName)

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
                    <h2 class="common-heading" style="border-bottom:8px solid #cbcbcb; font-size:1.5em;">User Information</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <label>Status</label>
                    <p>{{ $user->Status->UserStatusName }}</p>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-6 col-sm-4">
                    <label>First Name</label>
                    <p>{{ $user->FirstName ?: $message }}</p>
                </div>
                <div class="col-6 col-sm-4">
                    <label>Last Name</label>
                    <p>{{ $user->LastName ?: $message }}</p>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-6 col-sm-4">
                    <label>Email</label>
                    <p>{{ $user->EmailAddress ?: $message }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-6 col-sm-4">
                    <label>Phone</label>
                    <p>{{ App\Models\Util::formatPhoneNumber($user->PhoneNumber) ?: $message }}</p>
                </div>
                <div class="col-6 col-sm-4">
                    <label>Ext</label>
                    <p>{{ $user->PhoneExt ?: $message }}</p>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-6 col-sm-4">
                    <label>Users Group</label>
                    @if($user->SecurityGroup == 1)
                    <p>Administrator</p>
                    @elseif($user->SecurityGroup == 2)
                    <p>Reporting Group</p>
                    @endif
                </div>
            </div>
            @if($user->SecurityGroup != 1)
            <div class="row">
                <div class="col-12 col-md-4 col-sm-8">
                    <label>Districts</label>
                    <div class='DivisionDistrictsScroll'>
                        @foreach($districts as $d)
                            <a>{{$d['DivisionDistrictName']}}</a><br>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
            <hr>
            <div class="row">
                <div class="col-6 col-sm-4">
                    <label>User Name</label>
                    <p>{{ $user->UserName ?: $message }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-6 col-sm-4">
                    <label>Password</label>
                    <p>**********</p>
                </div>
                <div class="col-6 col-sm-4">
                  <a href="{{route('dashboard.users.resetPasswordEmail', $user->UserID)}}">
                      <i class="fa fa-envelope-o fa-ac"></i> Resend Password by Email
                  </a>
                </div>
            </div>
            <div class="row">
                <div class="col-6 col-sm-4">
                    <a href="{{route('dashboard.users.session',[ $user->UserID, 'edit'])}}"><i class="fa fa-undo fa-ac"></i> Reset Session</a>
                </div>
            </div>
        </div>
        <div class="row bottom_container_details">
            <div class="container">
                <div class="center">
                    <a style='color:white' href="{{route('dashboard.users.edit', $user->UserID)}}" class="btn-primary"><i class="fa fa-pencil"></i> Edit</a>
                </div>
            </div>
        </div>
    </div>
@endsection
