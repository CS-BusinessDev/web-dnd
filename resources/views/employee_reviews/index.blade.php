@extends('layout.main_tamplate')

@section('content')
    <section class="content-header">
        <!-- Konten Utama -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <!-- Notifikasi Sukses -->
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>{{ $message }}</strong>
                            </div>
                        @endif
                        <!-- Notifikasi Error -->
                        @if ($message = Session::get('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>{{ $message }}</strong>
                            </div>
                        @endif

                        @if (session('importMessage'))
                            <div class="alert alert-info">
                                {{ session('importMessage') }}
                            </div>
                        @endif

                        @if (session('skippedDetails') && count(session('skippedDetails')) > 0)
                            <div class="alert alert-warning">
                                <p>Data berikut dilewati karena sudah ada atau tidak valid:</p>
                                <ul>
                                    @foreach (session('skippedDetails') as $skipped)
                                        <li>{{ $skipped['nama_lengkap'] }} - Periode: {{ $skipped['periode'] }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="card">
                            <div class="card-header">
                                <div class="row d-inline-flex">
                                    <h3 class="card-title mt-2"><strong>{{ $title }}</strong></h3>
                                </div>
                                <div class="card-tools">
                                    <a href="{{ route('employee_reviews.create') }}" class="btn btn-success mr-2"
                                        data-toggle="tooltip" data-placement="top" title="Tambah Review">
                                        <i class="fas fa-plus"></i>
                                    </a>
                                    <button class="btn btn-success mr-1" data-toggle="modal" data-target="#importModal"
                                        data-toggle="tooltip" data-placement="top" title="Import User">
                                        <i class="fa fa-upload" style="color: white"></i>
                                    </button>
                                    <a href="{{ url('employee_reviews/download') }}" class="btn btn-warning"
                                        data-toggle="tooltip" data-placement="top" title="Download Template">
                                        <i class="fas fa-file-alt" style="color: white"></i>
                                    </a>
                                </div>
                            </div>

                            <!-- Tabel Review Karyawan -->
                            <div class="card-body table-responsive p-0" style="height: 500px;">
                                <table class="table table-hover table-head-fixed text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>Nama Lengkap</th>
                                            <th>Periode</th>
                                            <th>Responsivitas</th>
                                            <th>Pemecahan Masalah</th>
                                            <th>Kesediaan Membantu</th>
                                            <th>Inisiatif</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($employeeReviews as $review)
                                            <tr>
                                                <td>{{ $review->user->nama_lengkap }}</td>
                                                <td>{{ $review->periode }}</td>
                                                <td>{{ $review->responsiveness }}</td>
                                                <td>{{ $review->problem_solver }}</td>
                                                <td>{{ $review->helpfulness }}</td>
                                                <td>{{ $review->initiative }}</td>
                                                <td>
                                                    <!-- Tombol Lihat -->
                                                    <a href="{{ route('employee_reviews.show', $review->id) }}"
                                                        class="btn btn-info">Lihat</a>
                                                    <!-- Tombol Edit -->
                                                    <a href="{{ route('employee_reviews.edit', $review->id) }}"
                                                        class="btn btn-warning">Edit</a>
                                                    <!-- Tombol Hapus -->
                                                    <form action="{{ route('employee_reviews.destroy', $review->id) }}"
                                                        method="POST" style="display:inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger"
                                                            onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div>
        </section>
    </section>
    <!-- Modal Import Data -->
    <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Import Data {{ $title }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ url('employee_reviews/import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="file">Upload File Excel:</label>
                            <input type="file" class="form-control" name="file" required>
                        </div>
                        <button type="submit" class="btn btn-success">Import</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
