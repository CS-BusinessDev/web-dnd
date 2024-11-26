{{-- @extends('layout.main_tamplate')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>{{ $message }}</strong>
                        </div>
                    @endif
                    @if ($message = Session::get('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>{{ $message }}</strong>
                        </div>
                    @endif
                    <div class="card">
                        <div class="card-header bg-white">
                            <h3 class="card-title"><strong>KPI Category</strong></h3>
                            <div class="card-tools">
                            </div>
                        </div>
                        <div class="card-body table-responsive p-0" style="height: 500px;">
                            <table class="table table-striped table-head-fixed text-nowrap">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Category</th>
                                        <th style="text-align: right;">More</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($kpiCategories as $kpic)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $kpic->name }}</td>
                                            <td style="text-align: right;">
                                                <a href="/kpicategory/{{ $kpic->id }}/edit" style="color: orange;">
                                                    <span><i class="fas fa-edit"></i></span>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>`
            </div>
        </div>
    </section>

    <div class="modal fade" id="addKPICategory" tabindex="-1" role="dialog" aria-labelledby="addKPICategoryLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" action="/kpicategory">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addKPICategoryLabel">Add KPI Category</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3 col-lg-12">
                            <label for="name" class="form-label">Nama Category</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-info">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection --}}

@extends('layouts.pages.dashboard')

@section('content')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-lg-12 col-md-12">
                    <div class="page-header-title">
                        <h2 class="mb-0">{{ $title }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card table-card">
                <div class="card-body pb-0">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                     <th>No</th>
                                        <th>Category</th>
                                        <th style="text-align: right;">More</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kpiCategories as $kpic)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $kpic->name }}</td>
                                            <td style="text-align: right;">
                                                <a href="/kpicategory/{{ $kpic->id }}/edit" style="color: orange;">
                                                    <span><i class="fas fa-edit"></i></span>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
