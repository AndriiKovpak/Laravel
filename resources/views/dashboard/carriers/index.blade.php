@extends('layouts.dashboard')

@section('title', 'Carriers')

@section('content')
    @if(Session::has('flash_message'))
        <div class="alert alert-success">
            <a class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <div class="row">
                <div class="col-xs-2"><img src="{{ asset('assets/images/star-msg.png') }}"></div>
                <div class="col-xs-8">{!! session('flash_message') !!}</div>
            </div>
        </div>
    @endif
    <div class="main-container" style="padding-bottom:0">
            <!-- start container -->
            <div class="container">
                <div class="row">
                        <div class="col-12  col-lg-5">
                            <h1 class="carrier_header">Carriers</h1>
                        </div>
                        <div class="col-12 col-md-7 col-lg-5 searchCarriers">
                            <div class="option-ico second clearfix">
                                <ul>
                                    {{--<li><a href="#"><i class="fa fa-gear"></i></a></li>--}}
                                    <li class="col-md-12 pr-0" style="padding-left:0px;">
                                        <div class="search col-md-12 pr-0 col-xs-12 float-xs-right" style="padding-left:0px;">
                                            <form id="formCarrierSearch">
                                                <input type="hidden" id="show_hide" name="show_hide" value="hide">
                                                <input name="CarrierSearch" id="CarrierSearch" type="text" value="{{$searchString}}" class="form-control" placeholder="Search Carriers">
                                                <div class="search-btn"><a id="search_btn_carriers"><i
                                                                class="fa fa-search"></i></a></div>
                                            </form>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <!--
                            <div class="option-ico second clearfix">
                                <ul>
                                    <li style="width:100%; padding:0;">
                                        <form id="formCarrierSearch">
                                            <input type="hidden" id="show_hide" name="show_hide" value="hide">
                                            <input name="CarrierSearch" id="CarrierSearch" type="search" value="{{$searchString}}" class="form-control" placeholder="Search Carriers">
                                            <!--<div class="search-btn"><a href="javascript:void(0);" id="search_btn"><i class="fa fa-search"></i></a></div>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                    -->
                        </div>
                        <div class="col-md-4 col-lg-2 col-12 addNewCarrierDiv">
                            <div class="row">
                                <div class="col-md-1 col-2">
                                    <a id="show_carrier_list" href=""><i class="fa fa-cog fa-ac" aria-hidden="true"></i></a>
                                </div>
                                <div class="col-md-1 col-4 hideMob">
                                    <a id="show_carrier_list" href="">|</a>
                                </div>
                                <div class="col-md-7 col-lg-8 col-10 addCarrier">
                                    <a href="{{route('dashboard.carriers.create')}}">Add New Carrier</a>
                                </div>
                            </div>
                        </div>
                    @include('dashboard.carriers.partials.list_carriers_by')
                    <div class="col-md-12 ">
                        <div class="table-responsive">
                        <table class="carrier_table table table-striped table-sm text-xs-center">
                        <thead>
                        <tr>
                            <th>Carrier Name</th>
                            <th>Phone #</th>
                            <th style="text-align: center">Details</th>
                            <th style="text-align: center">Edit</th>
                            <th style="text-align: center">Delete</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($carriers as $c)
                            <tr>
                                <td>{{$c['CarrierName']}}</td>
                                <td>{{App\Models\Util::formatPhoneNumber($c['CarrierPhoneNum'])}}</td>
                                <td class="text-center">
                                    <a href="{{route('dashboard.carriers.show', $c)}}">
                                        <i class="fa fa-eye fa-ac"></i>
                                    </a>
                                </td>
                                <td class="text-center">
                                    <a href="{{route('dashboard.carriers.edit', $c)}}">
                                        <i class="fa fa-pencil fa-ac"></i>
                                    </a>
                                </td>
                                <td class="text-center">
                                    <a>
                                        <a href="#" data-confirmation-btn="true"><i class="fa fa-trash fa-ac" aria-hidden="true"></i></a>
                                    </a>
                                </td>
                                <td data-confirmation-body colspan="7" class="text-left">

                                    <form style="display: none;" method="POST" action="{{ route('dashboard.carriers.destroy', $c) }}">
                                        {!! csrf_field() !!} {!! method_field('DELETE') !!}
                                        <input type="hidden" name="p" value="{{ request('page') }}" />
                                    </form>

                                    <i class="fa fa-info-circle" aria-hidden="true"></i>
                                    Are you sure you want to delete this carrier?

                                    <a href="" data-delete-form>Yes</a>
                                    <a href="#" data-confirmation-btn="false">No</a>
                                </td>
                            </tr>
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
                            {{ $carriers->links() }}
                        </div>
                    </div>
                </div>
                <br>
            </div>
        <!-- end container -->
    </div>
@endsection
