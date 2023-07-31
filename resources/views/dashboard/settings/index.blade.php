@extends('layouts.dashboard')

@section('title', 'Settings')

@section('content')

    <!-- start main-container -->
    <div class="main-container settings">
        <!-- start container -->
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-lg-12">
                    <h1>Settings</h1>
                </div>

                <a class="col-lg-3 col-md-3 col-sm-6 col-xs-6 section" href="{{ route('dashboard.settings.ftp-folders.index') }}">
                    <div class="content">
                        <div class="title">FTP Image Folders</div>
                        <div class="text">Here you can access and upload the images pulled in from Databank and the Finance Center.
                        </div>
                    </div>
                </a>

                <a class="col-lg-3 col-md-3 col-sm-6 col-xs-6 section" href="{{ route('dashboard.settings.division-districts.index') }}">
                    <div class="content">
                        <div class="title">Districts</div>
                        <div class="text">Here you can setup new District codes or edit existing District codes.
                        </div>
                    </div>
                </a>

                <a class="col-lg-3 col-md-3 col-sm-6 col-xs-6 section" href="{{ route('dashboard.settings.service-types.index') }}">
                    <div class="content">
                        <div class="title">Service Types</div>
                        <div class="text">Here you can create new service types, edit or delete existing service types, or view all service types by corresponding category.
                        </div>
                    </div>
                </a>

                <a class="col-lg-3 col-md-3 col-sm-6 col-xs-6 section" href="{{ route('dashboard.settings.features.index') }}">
                    <div class="content">
                        <div class="title">Features</div>
                        <div class="text">Here you can create new features, edit or delete existing features, or view all features by corresponding category.
                        </div>
                    </div>
                </a>

                <a class="col-lg-3 col-md-3 col-sm-6 col-xs-6 section" href="{{ route('dashboard.settings.favorite-reports.index') }}">
                    <div class="content">
                        <div class="title">Favorite Reports</div>
                        <div class="text">Here you can manage your favorite reports that appear on your customized dashboard.
                        </div>
                    </div>
                </a>

                <a class="col-lg-3 col-md-3 col-sm-6 col-xs-6 section" href="{{ route('dashboard.settings.file-upload.index') }}">
                    <div class="content">
                        <div class="title">File Upload</div>
                        <div class="text">Here you can upload new circuits, edit existing circuits, and add or edit existing circuit features.
                        </div>
                    </div>
                </a>

                <a class="col-lg-3 col-md-3 col-sm-6 col-xs-6 section" href="{{ route('dashboard.settings.circuit-descriptions.index') }}">
                    <div class="content">
                        <div class="title">Circuit Descriptions</div>
                        <div class="text">Here you can create new circuit descriptions, edit existing circuit descriptions, and add or edit existing circuit descriptions.
                        </div>
                    </div>
                </a>

            </div>
        </div>
        <!-- end container -->
    </div>
    <!-- end main-container -->
@endsection