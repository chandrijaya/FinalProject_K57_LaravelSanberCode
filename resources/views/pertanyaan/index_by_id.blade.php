@extends('layouts.master')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ $pertanyaan->judul }}</h1><br>
                    <h6 style="color: grey;">Pertanyaan dibuat : {{ $pertanyaan->created_at }}</h6>
                    <h6 style="color: grey; line-height: 0;">Pertanyaan diperbaharui : {{ $pertanyaan->updated_at }}</h6>
                    <h6>Ditanya oleh {{ $pertanyaan->user->name }} </h6>

                    <h6 style="line-height: 0;">Reputasi penanya : 
                    @if (isset($reputasi_jawaban[$pertanyaan->user->name]))
                    {{ $reputasi_pertanyaan[$pertanyaan->user->name] + $reputasi_jawaban[$pertanyaan->user->name] }} 
                    @else
                    {{$reputasi_pertanyaan[$pertanyaan->user->name]}}
                    @endif
                    </h6>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/pertanyaan">Data Pertanyaan</a></li>
                        <li class="breadcrumb-item active">Jawaban</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"> Pertanyaan : <?php echo $pertanyaan->isi ; ?> </h3><br>
                            <!-- Upvote pertanyaan -->
                            <article class="post" data-pertanyaan_id="{{ $pertanyaan->id }}">
                                @if (Auth::user()->id != $pertanyaan->user_id)
                                <div class="interaction">
                                    <a href="#" class="vote">{{ Auth::user()->vote_pertanyaan()->where('pertanyaan_id', $pertanyaan->id)->first() ? Auth::user()->vote_pertanyaan()->where('pertanyaan_id', $pertanyaan->id)->first()->value == 1 ? 'Kamu upvote pertanyaan ini' : 'Upvote' : 'Upvote'  }}</a> |
                                    <a href="#" class="vote">{{ Auth::user()->vote_pertanyaan()->where('pertanyaan_id', $pertanyaan->id)->first() ? Auth::user()->vote_pertanyaan()->where('pertanyaan_id', $pertanyaan->id)->first()->value == -1 ? 'Kamu downvote pertanyaan ini' : 'Downvote' : 'Downvote'  }}</a>
                                </div>
                                @endif
                                <p id="sum_upvote"> Upvote : {{ $vote->where('pertanyaan_id', $pertanyaan->id)->get()->sum('value') }} </p>
                            </article>
                            <!-- End upvote pertanyaan -->
                            <ul class="pagination pagination-sm m-0 float-right">
                                @if (Auth::user()->id != $pertanyaan->user_id)
                                <li class="page-item">
                                    <button title="Jawab" type="button" class="btn btn-success btn-sm" data-toggle="modal" data-pertanyaan="{{ $pertanyaan->isi }}" data-path="/jawaban/{{ $pertanyaan->id }}" data-target="#jawab">
                                        <i class="fas fa-plus-square"> Jawab </i>
                                    </button>
                                </li>
                                @else
                                <li class="page-item">
                                    <a href="/pertanyaan/{{ $pertanyaan->id }}/edit">
                                        <button title="Edit" type="submit" class="btn btn-primary btn-sm ml-2">
                                            <i class="fas fa-pen-square"> Edit </i>
                                        </button>
                                    </a>
                                </li>
                                <li class="page-item">
                                    <form action="/pertanyaan/{{ $pertanyaan->id }}" method="post">
                                        @method('DELETE')
                                        @csrf
                                        <button title="Hapus" type="submit" class="btn btn-danger btn-sm ml-2">
                                            <i class="fas fa-minus-square"> Hapus </i>
                                        </button>
                                    </form>
                                </li>
                                @endif
                            </ul>
                        </div>

                        <!-- Modal Bootstrap Jawaban -->
                        <!-- Modal Bootstrap -->

                        <div class="modal fade" id="jawab" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Form Jawaban
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <h3></h3>
                                        <form role="form" action="/jawaban/{{$pertanyaan->id}}" method="post">
                                            @csrf
                                            <div class="form-group">
                                                <label for="message-text" class="col-form-label">Jawaban:</label>
                                                <textarea name="isi" class="form-control" id="message-text"></textarea>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Jawab</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- /.TABLE JAWABAN-->
                        <div class="card-body p-0">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">No.</th>
                                        <th>Jawaban</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1 ?>
                                    @foreach ($daftar_jawaban as $item => $jawaban)
                                    <tr>
                                        <td> {{ $i++ }} </td>
                                        <td>
                                            <?php  print_r($jawaban['isi']); ?>
                                            <p>Dijawab oleh : {{ $jawaban->user->name }} </p>
                                            <article class="post" data-jawaban_id="{{ $jawaban->id }}">
                                                @if (Auth::user()->id != $jawaban->user->id)
                                                <div class="interaction">
                                                    <a href="#" class="vote-jawaban">{{ Auth::user()->vote_jawaban()->where('jawaban_id', $jawaban->id)->first() ? Auth::user()->vote_jawaban()->where('jawaban_id', $jawaban->id)->first()->value == 1 ? 'Kamu upvote jawaban ini' : 'Upvote' : 'Upvote'  }}</a> |
                                                    <a href="#" class="vote-jawaban">{{ Auth::user()->vote_jawaban()->where('jawaban_id', $jawaban->id)->first() ? Auth::user()->vote_jawaban()->where('jawaban_id', $jawaban->id)->first()->value == -1 ? 'Kamu downvote jawaban ini' : 'Downvote' : 'Downvote'  }}</a>
                                                </div>
                                                @endif
                                                <p id="sum_upvote"> Upvote : {{ $vote_jawaban->where('jawaban_id', $jawaban->id)->get()->sum('value') }} </p>
                                            </article>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- END TABLE JAWABAN -->
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
@endsection

@push('scripts')

$.ajaxSetup({
headers: {
'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
}
});

var urlVote = '{{ route('vote') }}';
var token = '{{ Session::token() }}';
var pertanyaan_id = 0;

$('.vote').on('click', function(event) {
event.preventDefault();
pertanyaan_id = event.target.parentNode.parentNode.dataset['pertanyaan_id'];
var isVote = event.target.previousElementSibling == null;
$.ajax({
method: 'POST',
url: urlVote,
data: {isVote: isVote, pertanyaan_id: pertanyaan_id, _token: token},
success: function (data) {
console.log(data);
},
error: function (data, textStatus, errorThrown) {
console.log(data);

}
})
.done(function() {
event.target.innerText = isVote ? event.target.innerText == 'Upvote' ? 'Kamu upvote pertanyaan ini' : 'Upvote' : event.target.innerText == 'Downvote' ? 'Kamu downvote pertanyaan ini' : 'Downvote';
if (isVote) {
event.target.nextElementSibling.innerText = 'Downvote';
} else {
event.target.previousElementSibling.innerText = 'Upvote';
}
});

});

var urlVoteJawaban = '{{ route('vote-jawaban') }}';
var jawaban_id = 0;

$('.vote-jawaban').on('click', function(event) {
event.preventDefault();
jawaban_id = event.target.parentNode.parentNode.dataset['jawaban_id'];
var isVote = event.target.previousElementSibling == null;
$.ajax({
method: 'POST',
url: urlVoteJawaban,
data: {isVote: isVote, jawaban_id: jawaban_id, _token: token},
success: function (data) {
console.log(data);
},
error: function (data, textStatus, errorThrown) {
console.log(data);

}
})
.done(function() {
event.target.innerText = isVote ? event.target.innerText == 'Upvote' ? 'Kamu upvote jawaban ini' : 'Upvote' : event.target.innerText == 'Downvote' ? 'Kamu downvote jawaban ini' : 'Downvote';
if (isVote) {
event.target.nextElementSibling.innerText = 'Downvote';
} else {
event.target.previousElementSibling.innerText = 'Upvote';
}
});

});

$('#jawab').on('show.bs.modal', function (event) {
var button = $(event.relatedTarget)
var path = button.data('path')
var pertanyaan = button.data('pertanyaan')
var modal = $(this)

modal.find('.modal-body form').attr("action", path)
modal.find('.modal-body h3').html(pertanyaan)
})

{{-- script khusus summernote --}}
<script>
    $(document).ready(function() {
        $('#message-text').summernote(); // Ubah #message-text sesuai id pada tag textarea
    });
</script>
{{-- /script khusus summernote --}}


@endpush