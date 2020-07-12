@extends('layouts.master')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Form Pertanyaan</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/pertanyaan">Data Pertanyaan</a></li>
              <li class="breadcrumb-item active">Form Pertanyaan</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row justify-content-center">
          <!-- left column -->
          <div class="col-md-9">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Pertanyaan</h3>
              </div>
              <!-- /.card-header -->
              <p><br></p>
              <!-- form start -->
              <form role="form" action="/pertanyaan" method="post">
              @csrf <!-- Jangan lupa @csrf ya! -->
                <div class="card-body">
                  <div class="form-group">
                    <label for="judul">Judul</label>
                    <input type="text" class="form-control" name="judul" id="judul" placeholder="Masukkan judul">
                  </div>
                  <div class="form-group">
                    <label for="tags">Tag</label>
                    <input type="text" class="form-control" name="tags" id="tags" placeholder="Masukkan tag. Jika lebih dari satu, pisahkan dengan koma">
                  </div>
                  <p><br></p>
                  <!-- textarea -->
                  <div class="form-group">
                    <label>Isi</label>
                    <textarea class="form-control" name="isi"  placeholder="Masukkan pertanyaan" id="isi"></textarea>
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>
              </form>
            </div>
            <!-- /.card -->

        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
@endsection
{{-- script khusus summernote --}}
@push('scripts')
<script>
    $(document).ready(function() {
        $('#isi').summernote(); // Ubah #isi sesuai id pada tag textarea
    });
</script>
@endpush
{{-- /script khusus summernote --}}