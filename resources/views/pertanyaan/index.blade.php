@extends('layouts.master')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Data Pertanyaan</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/pertanyaan/create">Buat Pertanyaan</a></li>
                        <li class="breadcrumb-item active">Data Pertanyaan</li>
                    </ol>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Tabel Pertanyaan</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body p-0">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">No.</th>
                                        <th style="text-align: center">Pertanyaan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pertanyaan as $key => $item)
                                    <tr>
                                        <td> {{ $key+1 }} </td>
                                        <td>
                                            <input type="hidden" name="pertanyaan_id" value="{{ $item->id }}">
                                            <a href="/pertanyaan/{{ $item->id }}" onclick="get_id_pertanyaan()"><h3> {{ $item->judul }} </h3></a>
                                            <!-- Ini buat fitur like -->
                                            <article class="post" data-pertanyaan_id="{{ $item->id }}">
                                                <div>{{Str::limit(strip_tags($item->isi),300, '......')}}</div>
                                                @foreach($pertanyaan->find($item->id)->tags as $tag)
                                                <button class="btn btn-info btn-sm"> {{$tag->nama}} </button>
                                                @endforeach
                                                <div class="info">
                                                    Ditanya oleh {{ $item->user->name }} pada {{ $item->created_at }}
                                                </div>
                                                @auth
                                                @if (Auth::user()->id != $item->user_id)
                                                <div class="interaction">
                                                    <a href="#" class="vote">{{ Auth::user()->vote_pertanyaan()->where('pertanyaan_id', $item->id)->first() ? Auth::user()->vote_pertanyaan()->where('pertanyaan_id', $item->id)->first()->value == 1 ? 'Kamu upvote pertanyaan ini' : 'Upvote' : 'Upvote'  }}</a> |
                                                    <a href="#" class="vote">{{ Auth::user()->vote_pertanyaan()->where('pertanyaan_id', $item->id)->first() ? Auth::user()->vote_pertanyaan()->where('pertanyaan_id', $item->id)->first()->value == -1 ? 'Kamu downvote pertanyaan ini' : 'Downvote' : 'Downvote'  }}</a>
                                                </div>
                                                @endif
                                                @endauth
                                            </article>
                                            <!-- End fitur like -->
                                            <p class="float-left" id="sum_upvote"> Vote : {{ $vote->where('pertanyaan_id', $item->id)->get()->sum('value') }} </p>
                                            <ul class="pagination pagination-sm m-0 d-flex justify-content-end">
                                                @auth
                                                @if (Auth::user()->id != $item->user_id)
                                                <li class="page-item">
                                                    <button title="Jawab" type="button" class="btn btn-success btn-sm" data-toggle="modal" data-pertanyaan="{{ $item->isi }}" data-path="/jawaban/{{ $item->id }}" data-target="#jawab">
                                                        <i class="fas fa-plus-square"> Jawab </i>
                                                    </button>
                                                </li>
                                                @else
                                                <li class="page-item">
                                                    <a href="/pertanyaan/{{ $item->id }}/edit">
                                                        <button title="Edit" type="submit" class="btn btn-primary btn-sm ml-2">
                                                            <i class="fas fa-pen-square"> Edit </i>
                                                        </button>
                                                    </a>
                                                </li>
                                                <li class="page-item">
                                                    <form action="/pertanyaan/{{ $item->id }}" method="post">
                                                        @method('DELETE')
                                                        @csrf
                                                        <button title="Hapus" type="submit" class="btn btn-danger btn-sm ml-2">
                                                            <i class="fas fa-minus-square"> Hapus </i>
                                                        </button>
                                                    </form>
                                                </li>
                                                @endif
                                                @endauth
                                            </ul>
                                        </td>
                                        <td class="align-middle">
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
                                                            <form role="form" action="/jawaban/{{$item->id}}" method="post">
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
    </section>
    <!-- /.content -->
</div>
@endsection

@push('scripts')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var urlVote = "{{ route('vote') }}";
    var token = '{{ Session::token() }}';
    var pertanyaan_id = 0;
    var nama_user = "{{ Auth::user()->name }}";

    $('.vote').on('click', function (event) {
        event.preventDefault();
        pertanyaan_id = event.target.parentNode.parentNode.dataset['pertanyaan_id'];
        var isVote = event.target.previousElementSibling == null;
        $.ajax({
                method: 'POST',
                url: urlVote,
                data: {
                    isVote: isVote,
                    pertanyaan_id: pertanyaan_id,
                    _token: token,
                    nama_user: nama_user
                },
                success: function (data) {
                    console.log([data, nama_user]);
                },
                error: function (data, textStatus, errorThrown) {
                    console.log([data, nama_user]);
                    alert("Maaf, reputasi mu kurang dari 15 poin");
                }
            })
            .done(function () {
                event.target.innerText = isVote ? event.target.innerText == 'Upvote' ? 'Kamu upvote pertanyaan ini' : 'Upvote' : event.target.innerText == 'Downvote' ? 'Kamu downvote pertanyaan ini' : 'Downvote';
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
    });


    // script khusus summernote
    $(document).ready(function () {
        $('#message-text').summernote(); // Ubah #message-text sesuai id pada tag textarea
    });
    // script khusus summernote
</script>

@endpush