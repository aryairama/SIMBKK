<div class="modal fade create" tabindex="-1" role="dialog" id="modal_dialog" aria-hidden="true">
    <div class=" modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <form class="form_data_sekolah form-data" enctype="application/x-www-form-urlencoded">
                @csrf
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
                                <input class="form-control form-control-custom p-0" type="text" name="npsn" id="npsn"
                                    placeholder="NPSN">
                            </div>
                            <div class="form-group">
                                <input class="form-control form-control-custom p-0 mt-1" type="text" name="sekolah_nama"
                                    id="sekolah_nama" placeholder="Nama Sekolah">
                            </div>
                            <div class="form-group">
                                <input class="form-control form-control-custom p-0 mt-1" type="text"
                                    name="sekolah_kepsek" id="sekolah_kepsek" placeholder="Kepala Sekolah">
                            </div>
                            <div class="form-group">
                                <input class="form-control form-control-custom p-0 mt-1" type="email"
                                    name="sekolah_email" id="sekolah_email" placeholder="email">
                            </div>
                            <div class="form-group">
                                <input class="form-control form-control-custom p-0 mt-1" type="text" name="kec" id="kec"
                                    placeholder="Kecamatan">
                            </div>
                            <div class="form-group">
                                <input class="form-control form-control-custom p-0 mt-1" type="text" name="kab" id="kab"
                                    placeholder="Kabupaten">
                            </div>
                            <div class="form-group">
                                <input class="form-control form-control-custom p-0 mt-1" type="text" name="kode_pos"
                                    id="kode_pos" placeholder="Kode Pos">
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
