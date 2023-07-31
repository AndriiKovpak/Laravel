@extends('layouts.dashboard')

@section('title', 'Districts - Settings')
@section('content')

    <!-- start main-container -->
    <div class="main-container settings">
        <!-- start container -->
        <div class="container">
            <p>
                <a href="{{ route('dashboard.settings.index') }}">&lt; Back</a>
            </p>
            <div class="row">
                <div class="col-md-12 col-lg-12">
                    <h1>Districts</h1>
                </div>
                <div class="col-md-12 col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-striped table-sm text-xs-center">
                            <thead>
                            <tr>
                                <th style="width: 80%;">Districts</th>
                                @can('edit')
                                    <th>Edit</th>
                                    <th>Delete</th>
                                @endcan
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($divisionDistricts as $divisionDistrict)
                                <tr>
                                    <td>{{ $divisionDistrict->DivisionDistrictCode }}</td>
                                    @can('edit')
                                        <td>
                                            <a href="{{ route('dashboard.settings.division-districts.edit', [$divisionDistrict->DivisionDistrictID]) }}"><i class="fa fa-pencil fa-ac"></i></a>
                                        </td>
                                        <td>
                                            <a href="#" data-confirmation-btn="true"><i class="fa fa-trash fa-ac" aria-hidden="true"></i></a>
                                        </td>
                                        <td data-confirmation-body colspan="3" class="text-left">

                                            <form style="display: none;" method="POST" action="{{ route('dashboard.settings.division-districts.destroy', [$divisionDistrict->DivisionDistrictID]) }}">
                                                {!! csrf_field() !!} {!! method_field('DELETE') !!}
                                                <input type="hidden" name="p" value="{{ request('page') }}" />
                                            </form>

                                            <i class="fa fa-info-circle" aria-hidden="true"></i>
                                            Are you sure you want to delete it?

                                            <a href="" data-delete-form>Yes</a>
                                            <a href="#" data-confirmation-btn="false">No</a>
                                        </td>
                                    @endcan
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="no-highlight text-center"><h3 class="not-found">Sorry, No Districts Found</h3></td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-12 col-lg-12">
                        {{ $divisionDistricts->links()}}
                    </div>
                </div>
            </div>
            <!-- end container -->

        </div>
        <!-- end container -->
    </div>

    <div class="bottom-block">
        <div class="container">
            <div class="col-md-10 offset-md-1">
                <div class="row">
                    <div class="col-md-12 col-xs-12 text-center">
                        <button style="width:200px;" type="button" class="btn-primary"
                                data-toggle="modal" data-target=".settings-modal"
                                data-title="New District"
                                data-url="{{ route('dashboard.settings.division-districts.create') }}"
                        >NEW DISTRICT</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('dashboard.settings._partials.modal')

    <!-- end main-container -->
@endsection
