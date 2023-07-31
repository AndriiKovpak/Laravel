@extends('layouts.dashboard')

@section('title', 'FTP Image Folders - Settings')
@section('content')

    <!-- start main-container -->
    <div class="main-container settings ftp-folders">
        <!-- start container -->
        <div class="container">
            <p>
                <a href="{{ route('dashboard.settings.index') }}">&lt; Back</a>
            </p>
            <div class="row">
                <div class="col-md-12 col-lg-12">
                    <h1>FTP Image Folders</h1>
                    <button type="button" class="btn-primary btn-upload disabled">UPLOAD</button>
                </div>
                <div class="col-md-12 col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-sm text-xs-center table-hover table-clickable">
                            <thead>
                            <tr>
                                <th>Folder</th>
                                <th>Status</th>
                                <th># of Images</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(count($ftpFolders))
                                @foreach($ftpFolders as $ftpFolder)
                                    <tr data-can-change-status="{{ $ftpFolder->FTPFolderStatus == 4 }}">
                                        <td>{{ $ftpFolder->FilePath }}</td>
                                        <td>{{ $ftpFolder->FTPFolderStatusType ? $ftpFolder->FTPFolderStatusType->FTPFolderStatusName : 'No Status' }}</td>
                                        <td>{{ $ftpFolder->ImageCount }}</td>
                                        @if ($ftpFolder->FTPFolderStatus == 4)
                                        <td style="display: none;">
                                            <form method="POST" action="{{ route('dashboard.settings.ftp-folders.update', [$ftpFolder->FTPFolderID]) }}">
                                                {!! csrf_field() !!} {!! method_field('PUT') !!}
                                                 TODO change this after DB FTPFolderStatusType filled in
                                                <input type="hidden" name="FTPFolderStatus" value="2">
                                            </form>
                                        </td>
                                        @endif
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="8" class="no-highlight text-center"><h3 class="not-found">Sorry, No FTP Image Folders Found</h3></td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-12 col-lg-12">
                    {{ $ftpFolders->links()}}
                </div>
            </div>
            <!-- end container -->

        </div>
        <!-- end container -->
    </div>

    <!-- end main-container -->
@endsection
