<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});
Auth::routes();
Route::get('/register', function () {
    return redirect()->route('login');
});

Route::group(['middleware' => ['auth']], function () {
    Route::get('/dashboard', 'HomeController@index')->name('dashboard');
    Route::resource('user', 'UsersController');
    Route::resource('sekolah', 'SekolahController')->only([
        'index', 'store','edit','destroy'
    ]);
    Route::post('sekolah/update', 'SekolahController@update');
    Route::resource('keterserapan', 'KeterserapanController');
    Route::resource('komli', 'KomliController');
    Route::resource('angkatan', 'AngkatanController');
    Route::get('tableoperator', 'UsersController@datatableOperator');
    Route::get('tableadmin', 'UsersController@datatableAdmin');
    Route::get('selectsekolah', 'UsersController@selectNpsn');
    Route::resource('siswa', 'SiswaController');
    Route::get('selectsekolahnama', 'SiswaController@sekolahNama');
    Route::get('selectangkatannama', 'SiswaController@angkatanNama');
    Route::get('selectkomlinama', 'SiswaController@komliNama');
    Route::get('selectketerserapannama', 'SiswaController@keterserapanNama');
    Route::get('rekap', 'ExportExcelController@index')->name('rekap.index');
    Route::post('rekap/rekappersekolah', 'ExportExcelController@rekapSekolah')->name('export.per.sekolah');
    Route::get('rekap/rekapsemuasekolah', 'ExportExcelController@rekapSemuaSekolah')->name('export.semua.sekolah');
    Route::post('rekap/rekapangkatan', 'ExportExcelController@rekapAngkatan')->name('export.per.angkatan');
    Route::post('rekap/rekapkomli', 'ExportExcelController@rekapKomli')->name('export.per.komli');
    //Router Import Excel
    Route::get('import', 'ImportExcelController@index')->name('import.index');
    Route::post('import/preview', 'ImportExcelController@preview')->name('import.preview');
    Route::post('import/save', 'ImportExcelController@saveImportExcel')->name('import.save');
    Route::get('import/format', function () {
        return response()->download('data_siswa/format.xlsx');
    })->name('import.format');
    //Router ProfileSekolah
    Route::resource('profile', 'ProfileSekolahController');
});
// Route::get('test', 'ImportExcelController@test');
