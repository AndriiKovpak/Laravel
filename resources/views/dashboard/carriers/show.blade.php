@extends('layouts.dashboard')

@section('title', 'Carrier Details - '.$carrier->CarrierName)

@section('content')
    <div class="main-container" style="padding-bottom:0">
            <!-- start container -->
            <div class="container">
                <div class="row">
                    <div class="col-md-10 offset-md-1">
                        <a href="{{ route('dashboard.carriers.index') }}" class="nav_button">&lt; Back</a>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-10 offset-md-1">
                        <h2 class="common-heading">Carrier Details</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-10 offset-md-1">
                        <div class="col-12" style="text-align:right; height:10px">
                            <a href="{{route('dashboard.carriers.edit', [$carrier])}}"><i class="fa fa-pencil"></i></a>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <label>Carrier Name</label>
                                <p>{{ $carrier->CarrierName ?: $message }}</p>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-6">
                                <label>Phone #</label>
                                <p>{{ App\Models\Util::formatPhoneNumber($carrier->CarrierPhoneNum) ?: $message }}</p>
                            </div>
                            <div class="col-6">
                                <label>Support Phone #</label>
                                <p>{{ App\Models\Util::formatPhoneNumber($carrier->CarrierSupportPhoneNum) ?: $message }}</p>
                            </div>
                        </div>
                        <br>

                        @if(count($contacts))
                            <div class="row border-bottom2" style="margin:0;">
                                <div class="col-6" style="padding:0">
                                    <h2 class="common-heading2">Carrier Contacts</h2>
                                </div>
                                <div class="col-6" style="text-align:right; padding:0;">
                                    <a href="{{ route('dashboard.carriers.contact.create', [$carrier]) }}">Add Carrier Contact</a>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="contacts_table table table-striped table-sm text-xs-center">
                                    <thead>
                                    <tr>
                                        <th>Contact Name</th>
                                        <th>Title</th>
                                        <th>Contact Email</th>
                                        <th>Mobile Phone #</th>
                                        <th>Office Phone #</th>
                                        <th style="text-align: center">Edit</th>
                                        <th style="text-align: center">Delete</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($contacts as $contact)
                                        <tr>
                                            <td>{{$contact['Name']}}</td>
                                            <td>{{$contact['Title']}}</td>
                                            <td>{{$contact['EmailAddress']}}</td>
                                            <td>{{App\Models\Util::formatPhoneNumber($contact['MobilePhoneNumber'])}}</td>
                                            <td>{{App\Models\Util::formatPhoneNumber($contact['OfficePhoneNumber'])}}</td>
                                            <td class="text-center">
                                                <a href="{{ route('dashboard.carriers.contact.edit', [$carrier, $contact]) }}">
                                                    <i class="fa fa-pencil fa-ac"></i>
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                <a>
                                                    <a href="#" data-confirmation-btn="true"><i class="fa fa-trash fa-ac" aria-hidden="true"></i></a>
                                                </a>
                                            </td>
                                            <td data-confirmation-body colspan="7" class="text-left">

                                                <form style="display: none;" method="POST" action="{{ route('dashboard.carriers.contact.destroy', [$carrier, $contact]) }}">
                                                    {!! csrf_field() !!} {!! method_field('DELETE') !!}
                                                    <input type="hidden" name="p" value="{{ request('page') }}" />
                                                </form>

                                                <i class="fa fa-info-circle" aria-hidden="true"></i>
                                                Are you sure you want to delete this carrier contact?

                                                <a href="" data-delete-form>Yes</a>
                                                <a href="#" data-confirmation-btn="false">No</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="pagination-links">
                                {{ $contacts->links() }}
                            </div>
                            @else
                            <hr>
                            <a href="{{ route('dashboard.carriers.contact.create', $carrier->CarrierID) }}">Add Carrier Contact</a>
                        @endif
                    </div>
                </div>
            </div>
        <br>
        <!--
            <div class="row bottom_container_details">
                <div class="container">
                    <div class="center">
                        <a style='color:white' href="route('dashboard.carriers.edit', $id)" class="btn-primary">Edit Carrier</a>
                    </div>
                </div>
            </div>
            -->
        <!-- end container -->
    </div>
@endsection
