@extends('layouts.dashboard')

@section('title', 'User Edit - '. $user->FullName)

@section('content')
    <div class="main-container" style="padding-bottom:0">
        <form id="user_form" style="margin:0" method="POST" enctype="multipart/form-data" action="{{ route('dashboard.users.update', [$user]) }}">
            {!! method_field('PUT') !!}
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <a href="{{route('dashboard.users.index',[
                                'ListBy' => request('ListBy'),
                                'page' => request('page'),
                                'UsersSearch' => request('UsersSearch')
                            ])}}" class="nav_button">&lt; Back</a>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="common-heading" style="border-bottom:8px solid #cbcbcb; font-size:1.5em;">User Edit</h2>
                    </div>
                </div>
                @include('dashboard.users.partials._form')
            </div>
            <div class="bottom_container_edit">
                <div class="container">
                    <div class="row">
                        <div class="col-md-5 offset-md-1 col-6">
                            <a href="{{route('dashboard.users.show', [$user])}}" class="btn-secondary">Cancel</a>
                        </div>
                        <div class="col-md-4 offset-md-1 col-6 text-right">
                            <input value="Save" class="btn-primary submit_user_form">
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
