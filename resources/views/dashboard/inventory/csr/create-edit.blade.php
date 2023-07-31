@extends('layouts.dashboard')

@section('title', (is_array($BTNAccountCSR) ? 'New CSR' : 'Edit CSR') . ' - ACCT # '.$BTNAccount->getAttribute('AccountNum'))

@section('content')
<div class="main-container inventory-circuits">
    <div class="container">
        <div class="row">

            <div class="col-lg-7">

                <div class="mt-3">
                    <form action="{{ route('dashboard.inventory.csr.index', [$BTNAccount]) }}" class="simple-search">
                        <input name="search" type="text" value="{{ request('search') }}" class="form-control search-field" placeholder="Search CSRs/Orders">
                        <button class="search-btn"><i class="fa fa-search"></i></button>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Account #/Description</th>
                                <th>Date Printed</th>
                                <th>View File</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($BTNAccountCSRs as $CSR)
                            <tr class="{{ (! is_array($BTNAccountCSR) && $BTNAccountCSR->getKey() == $CSR->getKey()) ? 'table-active' : '' }}">
                                <td>@notdefined($CSR['AccountNum'])</td>
                                <td>@notdefined($CSR['PrintedDate'], $CSR['PrintedDate']->format('m/d/Y'))</td>
                                <td>
                                    @if($CSR->documentExists())
                                    <a href="{{ route('dashboard.inventory.csr.show', [$BTNAccount, $CSR]) }}" target="_blank">
                                        @php($Extension = $CSR->getFileExtension())
                                        @if($Extension == 'pdf')
                                        <i class="fa fa-file-pdf-o"></i>
                                        @elseif($Extension == 'docx' || $Extension == 'doc')
                                        <i class="fa fa-file-word-o"></i>
                                        @elseif($Extension == 'xlsx' || $Extension == 'xls' || $Extension == 'csv')
                                        <i class="fa fa-file-excel-o"></i>
                                        @elseif($Extension == 'text' || $Extension == 'txt')
                                        <i class="fa fa-file-text-o"></i>
                                        @elseif(in_array($Extension, ['tif','tiff', 'jpg', 'jpeg', 'jpe', 'png']))
                                        <i class="fa fa-file-picture-o"></i>
                                        @else
                                        <i class="fa fa-file-o"></i>
                                        @endif
                                    </a>
                                    @else
                                    <i class="fa fa-ban text-muted" title="File is not found" aria-hidden="true"></i>
                                    @endif
                                </td>
                                <td><a href="{{ route('dashboard.inventory.csr.edit', array_merge([$BTNAccount, $CSR], request()->only(['search', 'page']))) }}"><i class="fa fa-pencil"></i></a></td>
                                <td>
                                    <a href="#" data-confirmation-btn="true"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                </td>
                                <td data-confirmation-body colspan="5" class="text-left">

                                    <form style="display: none;" method="POST" action="{{ route('dashboard.inventory.csr.destroy', [$BTNAccount, $CSR]) }}">
                                        {!! csrf_field() !!} {!! method_field('DELETE') !!}
                                        <input type="hidden" name="search" value="{{ request('search') }}" />
                                        <input type="hidden" name="page" value="{{ request('page') }}" />
                                    </form>

                                    <i class="fa fa-info-circle" aria-hidden="true"></i>
                                    Are you sure you want to mark it as deleted?

                                    <a href="" data-delete-form>Yes</a>
                                    <a href="#" data-confirmation-btn="false">No</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <hr />
                {!! $BTNAccountCSRs->appends(request()->only('search'))->links() !!}
            </div>

            <div class="col-lg-5 circuit-container">

                <div class="row circuit-content">

                    <div class="col-lg-12">
                        <strong>{{ is_array($BTNAccountCSR) ? 'New CSR' : 'Edit CSR' }}</strong>
                        <a href="{{ route('dashboard.inventory.csr.index', array_merge([$BTNAccount], request()->only(['search', 'page']))) }}" class="pull-right"><i class="fa fa-times"></i></a>
                        <hr />
                    </div>

                    <div class="col-lg-12">
                        <form method="POST" action="{{ is_array($BTNAccountCSR) ? route('dashboard.inventory.csr.store', [$BTNAccount]) : route('dashboard.inventory.csr.update', [$BTNAccount, $BTNAccountCSR]) }}" enctype="multipart/form-data">

                            @if(! is_array($BTNAccountCSR))
                            {!! method_field('PUT"') !!}
                            @endif

                            {!! csrf_field() !!}
                            <input type="hidden" name="search" value="{{ request('search') }}" />
                            <input type="hidden" name="page" value="{{ request('page') }}" />

                            <div class="row">
                                <div class="col-md-12 mt-1">
                                    <div class="form-group {{ $errors->has('AccountNum') ? 'has-danger' : '' }}">
                                        <label class="d-block" for="AccountNum">Account #/Description</label>
                                        <div class="d-block">
                                            <input type="text" id="AccountNum" name="AccountNum" maxlength="50" class="form-control" value="{{ old('AccountNum', Arr::get($BTNAccountCSR, 'AccountNum')) }}">
                                            <span class="text-danger form-text">{{ $errors->first('AccountNum') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 mt-1">
                                    <div class="form-group {{ $errors->has('PrintedDate') ? 'has-danger' : '' }}">
                                        <label class="d-block" for="PrintedDate">Date Printed</label>
                                        <div class="d-block">
                                            <input type="date" id="PrintedDate" name="PrintedDate" maxlength="50" class="form-control" value="{{ old('PrintedDate', Arr::has($BTNAccountCSR, 'PrintedDate') ? $BTNAccountCSR['PrintedDate']->format('Y-m-d') : null) }}">
                                            <span class="text-danger form-text">{{ $errors->first('PrintedDate') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 mt-1">
                                    <div class="form-group {{ $errors->has('File') ? 'has-danger' : '' }}">
                                        <label class="d-block">File</label>
                                        <div class="d-block">
                                            <ul>
                                                @if(!empty($BTNAccountCSR) && $BTNAccountCSR->FilePath)
                                                <li>
                                                    <a href="{{ route('dashboard.inventory.csr.downloadCSR', [$BTNAccount, $BTNAccountCSR]) }}" target="_blank" class="text-muted ml-2">
                                                        @php($Extension = pathinfo($BTNAccountCSR->FilePath)['extension'])
                                                        @if($Extension == 'pdf')
                                                        <i class="fa fa-file-pdf-o"></i> Download CSR PDF
                                                        @elseif($Extension == 'docx' || $Extension == 'doc')
                                                        <i class="fa fa-file-word-o"></i> Download CSR Word Document
                                                        @elseif($Extension == 'xlsx' || $Extension == 'xls' || $Extension == 'csv')
                                                        <i class="fa fa-file-excel-o"></i> Download CSR Excel Document
                                                        @elseif($Extension == 'text' || $Extension == 'txt')
                                                        <i class="fa fa-file-text-o"></i> Download CSR Text Document
                                                        @elseif(in_array($Extension, ['tif','tiff', 'jpg', 'jpeg', 'jpe', 'png']))
                                                        <i class="fa fa-file-picture-o"></i> Download CSR Image
                                                        @else
                                                        <i class="fa fa-file-o"></i> Download CSR File
                                                        @endif
                                                    </a>
                                                </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                @if(empty($BTNAccountCSR->FilePath))
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="file" name="File" />
                                        <span class="text-danger form-text">{{ $errors->first('File') }}</span>
                                    </div>
                                </div>
                                @endif
                            </div>

                            <div class="bottom-block">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-12 col-xs-12">
                                            <a href="{{ route('dashboard.inventory.csr.index', array_merge([$BTNAccount], request()->only(['search', 'page']))) }}" class="btn-secondary">CANCEL</a>
                                            <button type="submit" class="btn-primary pull-right">SAVE</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
