<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('divisi', 'Divisi::index');
$routes->get('divisi/tambah_divisi', 'Divisi::tambah_divisi');
$routes->post('divisi/simpan_divisi', 'Divisi::aksi_tambah_divisi');
$routes->post('divisi/update/(:num)', 'Divisi::update_divisi/$1');
$routes->get('divisi/edit_divisi/(:num)', 'Divisi::edit_divisi/$1');
$routes->get('divisi/dihapus_divisi', 'Divisi::dihapus_divisi');
$routes->get('divisi/delete_divisi/(:num)', 'Divisi::delete_divisi/$1');
$routes->get('divisi/restore_divisi/(:num)', 'Divisi::restore_divisi/$1');
$routes->get('divisi/hapus_divisi/(:num)', 'Divisi::hapus_divisi/$1');
$routes->get('divisi/detail_divisi/(:num)', 'Divisi::detail_divisi/$1');

$routes->get('hak_akses', 'HakAkses::index');
$routes->get('hak_akses/tambah_form', 'HakAkses::tambah_form');
$routes->post('hak_akses/aksi_tambah_form', 'HakAkses::aksi_tambah_form');
$routes->get('hak_akses/edit_form/(:num)', 'HakAkses::edit_form/$1');
$routes->post('hak_akses/aksi_edit_form', 'HakAkses::aksi_edit_form');
$routes->get('hak_akses/aksi_delete_form/(:num)', 'HakAkses::aksi_delete_form/$1');
$routes->get('hak_akses/tambah_level_permission', 'HakAkses::tambah_level_permission');
$routes->post('hak_akses/aksi_tambah_level', 'HakAkses::aksi_tambah_level');
$routes->get('hak_akses/edit_level_permission/(:num)', 'HakAkses::edit_level_permission/$1');
$routes->post('hak_akses/aksi_edit_level', 'HakAkses::aksi_edit_level');
$routes->get('hak_akses/aksi_delete_level/(:num)', 'HakAkses::aksi_delete_level/$1');
$routes->get('hak_akses/assign_user_level', 'HakAkses::assign_user_level');
$routes->post('hak_akses/aksi_assign_user_level', 'HakAkses::aksi_assign_user_level');
$routes->get('hak_akses/get_permissions_by_level/(:num)', 'HakAkses::get_permissions_by_level/$1');
$routes->get('hak_akses/get_user_level/(:num)', 'HakAkses::get_user_level/$1');
$routes->get('hak_akses/deleted_items', 'HakAkses::deleted_items');
$routes->get('hak_akses/aksi_restore_form/(:num)', 'HakAkses::aksi_restore_form/$1');
$routes->get('hak_akses/aksi_restore_level/(:num)', 'HakAkses::aksi_restore_level/$1');
$routes->get('hak_akses/aksi_restore_user_level/(:num)', 'HakAkses::aksi_restore_user_level/$1');
$routes->get('hak_akses/aksi_permanent_delete_form/(:num)', 'HakAkses::aksi_permanent_delete_form/$1');
$routes->get('hak_akses/aksi_permanent_delete_level/(:num)', 'HakAkses::aksi_permanent_delete_level/$1');
$routes->get('hak_akses/aksi_permanent_delete_user_level/(:num)', 'HakAkses::aksi_permanent_delete_user_level/$1');



$routes->get('penjualan', 'Penjualan::index');
$routes->get('penjualan/tambah_penjualan', 'Penjualan::tambah_penjualan');
$routes->post('penjualan/simpan_penjualan', 'Penjualan::aksi_tambah_penjualan');
$routes->post('penjualan/update/(:num)', 'Penjualan::update_penjualan/$1');
$routes->get('penjualan/edit_penjualan/(:num)', 'Penjualan::edit_penjualan/$1');
$routes->get('penjualan/dihapus_penjualan', 'Penjualan::dihapus_penjualan');
$routes->get('penjualan/delete_penjualan/(:num)', 'Penjualan::delete_penjualan/$1');
$routes->get('penjualan/restore_penjualan/(:num)', 'Penjualan::restore_penjualan/$1');
$routes->get('penjualan/hapus_penjualan/(:num)', 'Penjualan::hapus_penjualan/$1');
$routes->get('penjualan/detail_penjualan/(:num)', 'Penjualan::detail_penjualan/$1');
 
$routes->get('user', 'User::index');
$routes->get('user/tambah_user', 'User::tambah_user');
$routes->post('user/simpan_user', 'User::aksi_tambah_user');
$routes->post('user/update_user/(:num)', 'User::update_user/$1');
$routes->get('user/edit_user/(:num)', 'User::edit_user/$1');
$routes->get('user/dihapus_user', 'User::dihapus_user');
$routes->get('user/delete_user/(:num)', 'User::delete_user/$1');
$routes->get('user/restore_user/(:num)', 'User::restore_user/$1');
$routes->get('user/hapus_user/(:num)', 'User::hapus_user/$1');
$routes->get('user/detail_user/(:num)', 'User::detail_user/$1');
$routes->post('user/upload_to_gallery', 'User::upload_to_gallery');
$routes->get('user/get_image_library', 'User::get_image_library');

$routes->get('log_activity', 'LogActivity::index');
$routes->post('log_activity/delete', 'LogActivity::delete');

$routes->get('translate', 'Translate::index');

$routes->get('rapat', 'Rapat::index');
$routes->get('rapat/tambah_rapat', 'Rapat::tambah_rapat');
$routes->post('rapat/simpan_rapat', 'Rapat::aksi_tambah_rapat');
$routes->post('rapat/update/(:num)', 'Rapat::update_rapat/$1');
$routes->get('rapat/edit_rapat/(:num)', 'Rapat::edit_rapat/$1');
$routes->get('rapat/dihapus_rapat', 'Rapat::dihapus_rapat');
$routes->get('rapat/delete_rapat/(:num)', 'Rapat::delete_rapat/$1');
$routes->get('rapat/restore_rapat/(:num)', 'Rapat::restore_rapat/$1');
$routes->get('rapat/hapus_rapat/(:num)', 'Rapat::hapus_rapat/$1');
$routes->get('rapat/detail_rapat/(:num)', 'Rapat::detail_rapat/$1');
$routes->get('rapat/get_peserta/(:num)', 'Rapat::get_peserta/$1');
$routes->post('rapat/simpan_peserta', 'Rapat::simpan_peserta');
$routes->post('rapat/simpan_peserta_single', 'Rapat::simpan_peserta_single');

$routes->get('setting', 'Setting::index');
$routes->post('setting/update', 'Setting::update');

// Transkrip routes
$routes->group('transkrip', function ($routes) {
    $routes->get('/', 'Transkrip::index'); // halaman utama transkrip
    $routes->get('view/(:num)', 'Transkrip::view/$1');
    $routes->get('tambah', 'Transkrip::tambah_transkrip'); // form tambah transkrip
    $routes->post('upload', 'Transkrip::upload'); // upload + transkrip audio
    $routes->post('simpan', 'Transkrip::aksi_tambah_transkrip'); // simpan data transkrip ke DB
    $routes->post('update/(:num)', 'Transkrip::update_transkrip/$1');
    $routes->post('delete_selected', 'Transkrip::delete_selected');

    $routes->get('edit/(:num)', 'Transkrip::edit_transkrip/$1');
    $routes->get('dihapus', 'Transkrip::dihapus_transkrip');
    $routes->get('delete/(:num)', 'Transkrip::delete_transkrip/$1');
    $routes->get('restore/(:num)', 'Transkrip::restore_transkrip/$1');
    $routes->get('hapus/(:num)', 'Transkrip::hapus_transkrip/$1');
    $routes->get('detail/(:num)', 'Transkrip::detail_transkrip/$1');

    // peserta
    $routes->get('get_peserta/(:num)', 'Transkrip::get_peserta/$1');
    $routes->post('simpan_peserta', 'Transkrip::simpan_peserta');
    $routes->post('simpan_peserta_single', 'Transkrip::simpan_peserta_single');
    $routes->get('export/(:num)/(:alpha)', 'Transkrip::export/$1/$2');
});

$routes->get('/', 'Home::index');
$routes->get('/newsletter', 'Home::newsletter');
$routes->get('/contact', 'Home::contact');
$routes->get('Admin', 'Admin::index');
$routes->get('Katalog', 'Katalog::index'); 
$routes->get('/login', 'Login::index');
$routes->post('login/aksi_login', 'Login::aksi_login');
$routes->get('login/logout', 'Login::logout');
$routes->post('register', 'Login::register');
$routes->get('register', 'Login::register');
$routes->post('verify', 'Login::verify');
$routes->get('verifikasi', 'Login::form_verifikasi');
$routes->get('send-whatsapp', 'Login::sendWhatsAppCode');
$routes->get('forgot-password', 'Login::forgot_password');
$routes->post('forgot-password', 'Login::forgot_password');
$routes->get('reset-password', 'Login::reset_password');
$routes->post('reset-password', 'Login::reset_password');
$routes->post('transkrip/autosummary', 'Transkrip::autosummary');
$routes->post('/Api', 'Api::index');
$routes->post('api', 'Api::index');


$routes->get('profile', 'Profile::index');
$routes->post('/profile/update/(:num)', 'Profile::update/$1');
$routes->post('/profile/reset_password/(:num)', 'Profile::reset_password/$1');
$routes->post('profile/upload_to_gallery', 'Profile::upload_to_gallery');
$routes->get('profile/get_image_library', 'Profile::get_image_library');



// Cron routes for automated tasks
$routes->get('cron/send-meeting-reminders', 'Cron::sendMeetingReminders');




