<div class="modal fade create" tabindex="-1" role="dialog" id="modal_dialog" aria-hidden="true">
    <div class=" modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <form class="form_data_angkatan form-data" enctype="multipart/form-data">
                @csrf
                @method("POST")
                <input type="hidden" name="angkatan_id" id="angkatan_id">
                <div class="modal-header">
                    <h5 class="modal-title text-white h4"></h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <input class="form-control form-control-custom p-0" type="text" name="angkatan_ket"
                                    id="angkatan_ket" placeholder="Angkatan 2020/2021">
                            </div>
                        </div>
                    </div>
                    <div class="container-fluid">
                        <div class="row content-table">
                        </div>
                    </div>
                    {{-- isi --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-close" data-dismiss="modal">Tutup</button>
                    <input type="submit" value="Simpan" class="btn btn-success btn-submit">
                </div>
            </form>
        </div>
    </div>
</div>
