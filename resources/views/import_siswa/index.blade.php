@extends('layouts.global')
@section('title')
Import Data Siswa
@endsection
@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.css">
@endsection
@section('content')
<div class="page-header">
    <h4 class="page-title">
        Import Siswa
    </h4>
</div>
<div class="row">
    <div class="col-md-12">
        @can('roleOperatorSekolah')
        <div class="card">
            <div class="card-header">
                <div class="card-head-row">
                    <h4 class="card-title">Import Siswa</h4>
                    <div class="card-tools">
                        <a href="{{ route('import.format') }}" class="btn btn-primary btn-round btn-sm">Unduh Format
                            Import</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form action="#" method="POST" class="form-excel" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="export_siswa" name="export_siswa"
                            data-allowed-file-extensions="xlsx">
                    </div>
                    <button type="submit" class="btn btn-secondary mt-1" id="button_export">Preview</button>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="card-head-row">
                    <div class="card-title">Preview Import Excel</div>
                    <div class="card-tools remove-export"></div>
                </div>
            </div>
            <div class="card-body log_error">
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="data_table_export_siswa" class="display table table-hover nowrap  w-100 "
                        cellspacing="0">
                        <thead>
                            <tr>
                                <th>NISN</th>
                                <th>Nama Siswa</th>
                                <th>Angkatan</th>
                                <th>Tempat Lahir</th>
                                <th>Tanggal Lahir</th>
                                <th>JK</th>
                                <th>Komli</th>
                                <th>Prestasi</th>
                                <th>Keterserapan</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="preview_export_siswa"></tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer text-muted">
            </div>
        </div>
        @endcan
    </div>
</div>
<div class="preloader2 shadow shadow-lg rounded d-none">
    <div class="loading text-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only text-black-50">Loading...</span>
        </div>
        <p class="text-center mt-4 text-loader"></p>
    </div>
</div>
@endsection
@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.js"></script>
<script>
    var table = $('#data_table_export_siswa').DataTable();
    let drEvent = $('#export_siswa').dropify();
    $('.form-excel').on('submit',function(e){
        $('.remove-export').empty()
        $('.card-footer').empty()
        table.clear().draw();
        e.preventDefault()
        $.ajax({
            url : `{{ route('import.preview') }}`,
            type:"POST",
            data : new FormData($('.form-excel')[0]),
            processData: false,
            contentType: false,
            beforeSend : function(){
                $(".preloader2").removeClass('d-none').fadeIn("slow");
                $('.text-loader').html('Uploud File Axcel');
            },
            success : function(ress){
                $(".preloader2").addClass('d-none').fadeOut("slow");
                if(ress == 415){
                    notifAlert1("Error","Format excel tidak sesuai dengan format yang ada","error")
                } else if(ress == 501){
                    notifAlert1("Warning","File excel tidak boleh kosong","warning")
                } else if(ress == 422){
                    $('.dropify-wrapper').addClass('has-error')
                    $('.dropify-error').empty().html("File harus berformat .xlsx")
                    $('.card-footer').empty()
                    $(".dropify-clear").trigger("click");
                }else if(ress == 500){
                    notifAlert1("Error","File tidak bisa dibaca","error")
                } else {
                appendDataTable(ress)
                seveImportSiswa(ress)
                }
            },
            error : function(err){
                if(err.status == 403 ){
                    validPreview(err)
                    $(".dropify-clear").trigger("click");
                } else if(err.status == 422){
                    $('.dropify-wrapper').addClass('has-error')
                    $('.dropify-error').empty().html("Input file tidak boleh kosong")
                    $('.card-footer').empty()
                    $(".dropify-clear").trigger("click");
                }
                table.clear().draw();
                $('.card-footer').empty()
                $(".preloader2").addClass('d-none').fadeOut("slow");
            }
        })
    })
    function appendDataTable(data){
        $(".dropify-clear").trigger("click");
        $('.log_error').empty()
        table.clear().draw();
        $.each(data,function(index,value){
            table.row.add([
                value.nisn,
                value.siswa_nama,
                value.siswa_angkatan,
                value.tempat_lahir,
                value.tanggal_lahir,
                value.siswa_jk,
                value.siswa_komli,
                value.siswa_prestasi,
                value.siswa_keterserapan,
                value.keterangan
            ])
        })
        table.draw()
        $('.remove-export').empty().append('<button class="btn btn-danger btn-round btn-sm btn-remove-export">Hapus</button>')
        $('.card-footer').empty().append('<button class="btn btn-secondary save_import">Simpan</button>')
    }
    function seveImportSiswa(siswa){
        $('.save_import').on('click',function(){
            let data = JSON.stringify(siswa)
            let export_data = new FormData()
            export_data.append('export_data',data)
            $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })
            $.ajax({
                url: `{{ route('import.save') }}`,
                type: "POST",
                data : export_data,
                processData: false,
                contentType: false,
                beforeSend : function(){
                $('.text-loader').html('Proses simpan ke database');
                $(".preloader2").removeClass('d-none').fadeIn("slow");
                },
                success : function(ress){
                    $(".preloader2").addClass('d-none').fadeOut("slow");
                    if(ress == 200){
                        notifAlert1("Sukses","Data siswa berhasil disimpan","success")
                    }
                },
                error : function(err){
                }
            })
        })
    }
    function validPreview(data){
        $('.log_error').empty()
        table.clear().draw();
                    let all_error = JSON.parse(data.responseText)
                $.each(all_error,function(index,val){
                    if(val.angkatan !== null){
                        $('.log_error').append(`<p class="badge badge-danger">${ val.angkatan }</p>`)
                    }
                    if(val.komli !== null){
                        $('.log_error').append(`<p class="badge badge-danger">${ val.komli }</p>`)
                    }
                    if(val.keterserapan !== null){
                        $('.log_error').append(`<p class="badge badge-danger">${ val.keterserapan }</p>`)
                    }
                })
    }
    function notifAlert1(header, pesan, type) {
    Swal.fire(
        `${header}`,
        `${pesan}`,
        `${type}`
    ).then((result) => {
        if (result.isConfirmed) {
            $(".dropify-clear").trigger("click");
            $('.card-footer').empty()
            $('.remove-export').empty()
            table.clear().draw();
        }
    })
}

    $('.remove-export').on('click','.btn-remove-export',function(){
        table.clear().draw();
        $('.card-footer').empty()
        $('.remove-export').empty()
    })
</script>
@endsection
