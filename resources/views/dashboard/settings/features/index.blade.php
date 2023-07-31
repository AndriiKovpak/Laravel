@extends('layouts.dashboard')

@section('title', 'Features - Settings')
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
                    <h1>Features</h1>
                    <form>
                    <select name="category" class="form-control feature-category">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{$category->CategoryID}}" @if ($category->CategoryID == $selectedCategory) selected="selected" @endif>{{ $category->CategoryName }}</option>
                        @endforeach
                    </select>
                    </form>
                </div>
                <div class="col-md-12 col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-striped table-sm text-xs-center">
                            <thead>
                            <tr>
                                <th>Code</th>
                                <th>Feature Name</th>
                                <th>Category</th>
                                <th class="text-right">Edit</th>
                                <th class="text-right">Delete</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(count($featureTypes))
                                @foreach($featureTypes as $featureType)
                                    <tr>
                                        <td>{{ $featureType->FeatureCode }}</td>
                                        <td>{{ $featureType->FeatureName }}</td>
                                        <td>{{ $featureType->Category->CategoryName }}</td>
                                        <td class="text-right"><a href=""
                                               data-toggle="modal" data-target=".settings-modal"
                                               data-title="Edit Feature"
                                               data-url="{{ route('dashboard.settings.features.edit', [$featureType->FeatureType]) }}"
                                            ><img src="{{ asset('/assets/images/edit-icon.png') }}" alt="" ></a></td>
                                        <td class="text-right"><a href="" data-confirmation-btn="true"><img src="{{ asset('/assets/images/delete-icon.png') }}" alt=""></a></td>
                                        <td data-confirmation-body colspan="5" class="text-left">

                                            <form style="display: none;" method="POST" action="{{ route('dashboard.settings.features.destroy', [$featureType->FeatureType]) }}">
                                                {!! csrf_field() !!} {!! method_field('DELETE') !!}
                                                <input type="hidden" name="page" value="{{ request('page') }}" />
                                            </form>

                                            <i class="fa fa-info-circle" aria-hidden="true"></i>
                                            Are you sure you want to mark it as deleted?

                                            <a href="" data-delete-form>Yes</a>
                                            <a href="#" data-confirmation-btn="false">No</a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="8" class="no-highlight text-center"><h3 class="not-found">Sorry, No Features Found</h3></td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-12 col-lg-12">
                        {{ $featureTypes->appends(['category' => $selectedCategory])->links()}}
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
                        <button type="button" class="btn-primary"
                                data-toggle="modal" data-target=".settings-modal"
                                data-title="New Feature"
                                data-url="{{ route('dashboard.settings.features.create') }}"
                        >NEW FEATURE</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('dashboard.settings._partials.modal')

    <!-- end main-container -->
@endsection
