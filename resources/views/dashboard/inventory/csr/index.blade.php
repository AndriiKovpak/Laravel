@extends('layouts.dashboard')

@section('title', 'CSRs/Orders - ACCT # '.$BTNAccount->getAttribute('AccountNum'))

@section('content')
    <div class="main-container">
        <!-- start container -->
        <div class="container">
            <!-- start confirmation -->
            <div class="confirmation">

                <a href="{{ route('dashboard.inventory.index') }}" class="nav_button">&lt; Back</a>

                <h3 class="ml-3">ACCT # {{ $BTNAccount->getAttribute('AccountNum') }}</h3>

                @include('dashboard.inventory.partials.links', ['active' => 'csr'])

                <div class="clearfix"></div>

                <div class="col-sm-8 col-md-5 col-lg-4 float-xs-right mt-3">
                    <form action="{{ route('dashboard.inventory.csr.index', [$BTNAccount]) }}" class="simple-search">
                        <input name="search" type="text" value="{{ request('search') }}" class="form-control search-field" placeholder="Search CSRs/Orders">
                        <button class="search-btn"><i class="fa fa-search"></i></button>
                    </form>
                </div>

                <div class="tab-content mt-3">
                    <div class="tab-pane active">
                        <div class="table-responsive">
                            <table class="table table-striped table-sm text-xs-center">
                                <thead>
                                <tr>
                                    <th>Account #/Description</th>
                                    <th>Date Printed</th>
                                    <th>Download File</th>
                                    @can('edit')
                                        <th>Edit</th>
                                        <th>Delete</th>
                                    @endcan
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($CSRs as $CSR)
                                    <tr>
                                        <td>@notdefined($CSR['AccountNum'])</td>
                                        <td>@notdefined($CSR['PrintedDate'], $CSR['PrintedDate']->format('m/d/Y'))</td>
                                        <td>
                                            @if($CSR->documentExists())
                                                <a href="{{ route('dashboard.inventory.csr.show', [$BTNAccount, $CSR]) }}" target="_blank">
                                                    @php($Extension = $CSR->getFileExtension())
                                                    @if($Extension == 'pdf')
                                                        <i class="fa fa-file-pdf-o fa-ac"></i>
                                                    @elseif($Extension == 'docx' || $Extension == 'doc')
                                                        <i class="fa fa-file-word-o fa-ac"></i>
                                                    @elseif($Extension == 'xlsx' || $Extension == 'xls' || $Extension == 'csv')
                                                        <i class="fa fa-file-excel-o fa-ac"></i>
                                                    @elseif($Extension == 'text' || $Extension == 'txt')
                                                        <i class="fa fa-file-text-o fa-ac"></i>
                                                    @elseif(in_array($Extension, ['tif','tiff', 'jpg', 'jpeg', 'jpe', 'png']))
                                                        <i class="fa fa-file-picture-o fa-ac"></i>
                                                    @else
                                                        <i class="fa fa-file-o fa-ac"></i>
                                                    @endif
                                                </a>
                                            @else
                                                <i class="fa fa-ban text-muted" title="File is not found" aria-hidden="true"></i>
                                            @endif
                                        </td>
                                        @can('edit')
                                            <td>
                                                <a href="{{ route('dashboard.inventory.csr.edit', array_merge([$BTNAccount, $CSR], request()->only(['search', 'page']))) }}"><i class="fa fa-pencil fa-ac"></i></a>
                                            </td>
                                            <td>
                                                <a href="#" data-confirmation-btn="true"><i class="fa fa-trash fa-ac" aria-hidden="true"></i></a>
                                            </td>
                                            <td data-confirmation-body colspan="5" class="text-left">

                                                <form style="display: none;" method="POST" action="{{ route('dashboard.inventory.csr.destroy', [$BTNAccount, $CSR]) }}">
                                                    {!! csrf_field() !!} {!! method_field('DELETE') !!}
                                                    <input type="hidden" name="search" value="{{ request('search') }}" />
                                                    <input type="hidden" name="page" value="{{ request('page') }}" />
                                                </form>

                                                <i class="fa fa-info-circle fa-ac" aria-hidden="true"></i>
                                                Are you sure you want to mark it as deleted?

                                                <a href="" data-delete-form>Yes</a>
                                                <a href="#" data-confirmation-btn="false">No</a>
                                            </td>
                                        @endcan
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center no-highlight">
                                            <h3 class="not-found">No results displayed</h3>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        {!! $CSRs->appends(request()->only('search'))->links() !!}
                    </div>
                </div>
            </div>
            <!-- end confirmation -->
        </div>
        <!-- end container -->
    </div>
    <!-- end main-container -->
    @can('edit')
        <div class="bottom-block">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-xs-12 text-center">
                        <a href="{{ route('dashboard.inventory.csr.create', array_merge([$BTNAccount], request()->only(['search', 'page']))) }}" class="btn-primary">NEW CSR</a>
                    </div>
                </div>
            </div>
        </div>
    @endcan
    <!-- end main-container -->
@endsection
