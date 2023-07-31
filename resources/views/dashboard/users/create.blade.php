@extends('layouts.dashboard')

@section('title', 'New User')

@section('content')
    <div class="main-container" style="padding-bottom:0">
        <form id="user_form" action="{{ route('dashboard.users.store') }}" method="post" style="margin:0">
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
                        <h2 class="common-heading" style="border-bottom:8px solid #cbcbcb; font-size:1.5em;">New User</h2>
                    </div>
                </div>
                @include('dashboard.users.partials._form')
            </div>
            <div class="bottom_container_edit">
                <div class="container">
                    <div class="row">
                        <div class="col-md-5 offset-md-1 col-6">
                            <a href="{{route('dashboard.users.index')}}" class="btn-secondary">Cancel</a>
                        </div>
                        <div class="col-md-4 offset-md-1 col-6 text-right">
                            <input value="Save" type='submit' class="btn-primary submit_user_form">
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
