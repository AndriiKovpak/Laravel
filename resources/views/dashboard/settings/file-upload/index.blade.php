@extends('layouts.dashboard')

@section('title', 'File Upload - Settings')
@section('content')
    <div class="main-container settings file-upload" style="padding-bottom:0;min-height:0;">
        <form method="post" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <!-- start container -->
            <div class="container">
                <p>
                    <a href="{{ route('dashboard.settings.index') }}">&lt; Back</a>
                </p>
                <div class="row">
                    <div class="col-12">
                        <h1>File Upload</h1>
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if (isset($successMessage))
                            <div class="alert alert-success">
                                {{ $successMessage }}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="row">

                    <div class="col-lg-3 col-sm-6 d-flex align-items-stretch flex-column">
                        <h2>1. Select a file type</h2>
                        <p>This determines what type of data you would like to upload. Each file type has its own record format.</p>
                        <div class="form-group mt-auto">
                            <label for="file-type">File Type</label>
                            <select class="form-control" id="file-type" name="file-type">
                                <option value="New Circuits" @if (old('file-type')=='New Circuits') selected="selected" @endif>New Circuits</option>
                                <option value="Update Circuits" @if (old('file-type')=='Update Circuits') selected="selected" @endif>Update Circuits</option>
                                <option value="Circuit Features" @if (old('file-type')=='Circuit Features') selected="selected" @endif>Circuit Features</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-6 d-flex align-items-stretch flex-column">
                        <h2>2. Select a delimiter</h2>
                        <p>This tells us how the file was created. Most files will use comma delimiters.</p>
                        <div class="form-group mt-auto">
                            <label for="delimiter">Delimiter</label>
                            <select class="form-control" id="delimiter" name="delimiter">
                                <option value="Comma Delimited" @if (old('delimiter')=='Comma Delimited') selected="selected" @endif>Comma Delimited</option>
                                <option value="Tab Delimited" @if (old('delimiter')=='Tab Delimited') selected="selected" @endif>Tab Delimited</option>
                                <option value="Pipe Delimited" @if (old('delimiter')=='Pipe Delimited') selected="selected" @endif>Pipe Delimited</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-6 d-flex align-items-stretch flex-column">
                        <h2>3. Select a file</h2>
                        <p>Browse for the data file you would like to upload.</p>
                        <div class="form-group mt-auto">
                            <label for="file-upload">File</label>
                            {{--<input type="file" class="form-control-file" id="file" name="file-upload">--}}
                            {{--TODO: Test this across browsers and move the CSS.--}}
                            <style>
                                .new-custom-file {
                                    display: block;
                                }
                                .new-custom-file-input {
                                    width: 0;
                                    height: 0;
                                    display: block;
                                    overflow: hidden;
                                }
                                .new-custom-file-control {
                                    overflow: hidden;
                                    font-family: sans-serif;
                                    font-size: 12px;
                                    line-height: 28px;
                                    white-space: nowrap;
                                    text-overflow: ellipsis;
                                }
                                .form-control.focus {
                                    color: #464a4c;
                                    background-color: #ffffff;
                                    border-color: #c1c2c3;
                                    outline: none;
                                }
                            </style>
                            <label class="new-custom-file">
                                <input class="new-custom-file-input" type="file" id="file-upload" name="file-upload" onchange="$(this).next().text($(this).val().split(/[\\|/]/).pop() || 'Choose File&#8230;');" onfocus="$(this).next().addClass('focus')" onblur="$(this).next().removeClass('focus')">
                                <div class="form-control new-custom-file-control">Choose File&#8230;</div>
                            </label>
                        </div>
                    </div>

                    <div class="col-lg-3 col-sm-6 d-flex align-items-stretch flex-column">
                        <h2>4. Upload the file</h2>
                        <p>Press the "Upload" button to process the file.</p>
                    </div>
                </div>
            </div>
            <br>
            <div class="bottom_container_edit">
                <div class="container">
                    <div class="row">
                        <div class="col-6">
                            <a href="{{route('dashboard.settings.index')}}" class="btn-secondary">Cancel</a>
                        </div>
                        <div class="col-6 text-right">
                            <input type="submit" value="Upload" class="btn-primary" id="submit_file_upload">
                        </div>
                    </div>
                </div>
            </div>
            <!-- end container -->
        </form>
    </div>
@endsection