<div class="modal fade create" role="dialog" tabindex="-1" id="modal_dialog" aria-hidden="true">
    <div class=" modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <form class="form_data_siswa form-data" enctype="multipart/form-data">
                @csrf
                @method("POST")
                <input type="hidden" name="siswa_id" id="siswa_id">
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
                                <input class="form-control form-control-custom p-0" type="text" name="nisn" id="nisn"
                                    placeholder="NISN">
                            </div>
                            <div class="form-group">
                                <input class="form-control form-control-custom p-0" type="text" name="siswa_nama"
                                    id="siswa_nama" placeholder="Nama Siswa">
                            </div>
                            <div class="form-group">
                                <label for="siswa_angkatan">Tahun Angkatan</label>
                                <select name="siswa_angkatan" id="siswa_angkatan"
                                    class="form-control form-control-custom p-0" style="width: 100%">
                                </select>
                            </div>
                            <div class="form-group">
                                <input class="form-control form-control-custom p-0" type="text" name="tempat_lahir"
                                    id="tempat_lahir" placeholder="Tempat Lahir">
                            </div>
                            <div class="form-group">
                                <input class="form-control form-control-custom p-0" type="text" name="tanggal_lahir"
                                    id="tanggal_lahir" placeholder="Tanggal lahir dd/mm/yyyy">
                            </div>
                            <div class="form-check">
                                <label>Gender</label><br>
                                <label class="form-radio-label">
                                    <input class="form-radio-input" type="radio" name="siswa_jk" value="L" id="L">
                                    <span class="form-radio-sign">Laki-laki</span>
                                </label>
                                <label class="form-radio-label ml-3">
                                    <input class="form-radio-input" type="radio" name="siswa_jk" value="P" id="P">
                                    <span class="form-radio-sign">Perempuan</span>
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="siswa_komli">Jurusan</label>
                                <select name="siswa_komli" id="siswa_komli" class="form-control form-control-custom p-0"
                                    style="width: 100%"></select>
                            </div>
                            <div class="form-group">
                                <input class="form-control form-control-custom p-0" type="text" name="siswa_prestasi"
                                    id="siswa_prestasi" placeholder="Prestasi Siswa">
                            </div>
                            <div class="form-group">
                                <label for="siswa_keterserapan">Keterserpan Siswa</label>
                                <select name="siswa_keterserapan" id="siswa_keterserapan"
                                    class="form-control form-control-custom p-0" style="width: 100%"></select>
                            </div>
                            <div class="form-group">
                                <input class="form-control form-control-custom p-0" type="text" name="keterangan"
                                    id="keterangan" placeholder="Keterangan Keterserapan">
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
