@extends('layouts.master')

@section('content')
<h2>Buat Pertanyaan Baru</h2>
<p><br></p>
<div class="ml-3 mr-3">
    <form action="/forum" method="POST">
        @csrf
        <div class="form-group">
            <label for="article">Judul Pertanyaan</label>
            <input type="text" class="form-control" name="q_title" placeholder="Buat judul pertanyaan" id="q_title">
            <p><br></p>
            <label for="article">Artikel</label>
            <textarea type="text" class="form-control" name="q_content" placeholder="Buat isi pertanyaan" id="q_content"></textarea>
            <p><br></p>
            <button type="submit" class="btn btn-primary">Create</button>
            <a href="../../forum" class="btn btn-primary bg-danger">Cancel</a>
        </div>
    </form>
</div>
@endsection
{{-- script khusus summernote --}}
@push('scripts')
<script>
    $(document).ready(function() {
        $('#q_content').summernote(); // Ubah #q_content sesuai id pada tag textarea
    });
</script>
@endpush
{{-- /script khusus summernote --}}