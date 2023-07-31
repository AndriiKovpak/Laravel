@extends('layouts.dashboard')

@section('title', 'Circuit Descriptions - Settings')
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
                    <h1>Circuit Descriptions</h1>
                </div>
                <div class="col-md-12 col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-striped table-sm text-xs-center">
                            <thead>
                            <tr>
                                <th>Circuit Description</th>
                                <th class="text-right">Edit</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($CircuitDescriptions as $description)
                                <tr>
                                    <td>{{ $description->Description }}</td>
                                    <td class="text-right"><a href=""
                                                              data-toggle="modal" data-target=".settings-modal"
                                                              data-title="Edit Circuit Description"
                                                              data-url="{{ route('dashboard.settings.circuit-descriptions.edit', [$description->DescriptionID]) }}"
                                        ><img src="{{ asset('/assets/images/edit-icon.png') }}" alt="" ></a></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="no-highlight text-center"><h3 class="not-found">Sorry, No Circuit Descriptions Found</h3></td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-12 col-lg-12">
                        {{ $CircuitDescriptions->links()}}
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
                                data-title="New Circuit Description"
                                data-url="{{ route('dashboard.settings.circuit-descriptions.create') }}"
                        >NEW CIRCUIT DESCRIPTION</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('dashboard.settings._partials.modal')

    <!-- end main-container -->
@endsection
