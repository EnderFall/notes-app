<style>
    /* Batasi lebar kolom teks panjang dan wrap biar nggak nabrak */
    @media (max-width: 768px) {

        #table-rapat td,
        #table-rapat th {
            white-space: nowrap;
            font-size: 13px;
            padding: 6px 8px;
        }

        .card-header .d-flex {
            flex-direction: column;
            gap: 10px;
        }

        .btn {
            font-size: 14px;
            padding: 6px 12px;
        }

        .breadcrumb {
            font-size: 13px;
        }
    }
</style>

<div id="main-content">
    <div class="container-fluid">
        <div class="page-heading">
            <div class="page-title">
                <div class="row">
                    <div class="col-12 col-md-6 order-md-1 order-last">
                        <h3><?= $title ?></h3>
                        <p class="text-subtitle text-muted">Anda dapat melihat <?= $title ?> di bawah</p>
                    </div>
                    <div class="col-12 col-md-6 order-md-2 order-first">
                        <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page"><?= $title ?></li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible show fade">
                    <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible show fade">
                    <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php
            $route = service('uri')->getSegment(1);
            $formModel = new \App\Models\M_form();
            $form = $formModel->where('route', $route)->where('status_delete', 0)->first();
            $can_create = 0;
            $can_approve = 0;
            if ($form) {
                $groupFormModel = new \App\Models\M_group_form();
                $perm = $groupFormModel->where('id_form', $form['id_form'])->where('id_level', session()->get('level'))->where('status_delete', 0)->first();
                if ($perm && $perm['can_create'] == 1) {
                    $can_create = 1;
                }
                if ($perm && $perm['can_approve'] == 1) {
                $can_approve = 1;
            }
            }
            ?>

            <section class="section">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex gap-2">
                            <?php if ($can_create): ?>
                                <a href="<?= base_url('rapat/tambah_rapat/') ?>">
                                    <button class="btn btn-primary mt-2">
                                        <i class="fa-solid fa-plus"></i> Tambah
                                    </button>
                                </a>
                            <?php endif; ?>

                            <?php if ($can_approve): ?>
                                <a href="<?= base_url('rapat/dihapus_rapat') ?>">
                                    <button class="btn btn-secondary mt-2">
                                        Data rapat yang Dihapus
                                    </button>
                                </a>
                            <?php endif; ?>
                        </div>

                    </div>

                    <div class="card-body">
                        
                        <div class="table-responsive">
                            <table class="table table-striped table-sm" id="table-rapat">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Judul</th>
                                        <th>Tanggal</th>
                                        <th>Lokasi</th>
                                        <th>Keterangan</th>
                                        <th>Divisi</th>
                                        <?php if ($can_create): ?>
                                        <th>Peserta</th> <!-- ✅ kolom baru -->
                                        <?php endif; ?>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1;
                                    foreach ($rapat as $b) { ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= $b->judul ?></td>
                                            <td>
                                                <?= date('d F Y, H:i', strtotime($b->tanggal)) ?>
                                            </td>

                                            <td><?= $b->lokasi ?></td>
                                            <td><?= $b->keterangan ?></td>
                                            <td><?= $b->divisi ?></td>
                                            <?php if ($can_create): ?>
                                            <td>
                                                <button class="btn btn-sm btn-info btn-pilih-peserta"
                                                    data-id="<?= $b->id_rapat ?>">
                                                    <i class="fa-solid fa-users"></i> Pilih
                                                </button>
                                            </td>
                                            <?php endif; ?>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-sm btn-secondary dropdown-toggle"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fa fa-cog"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item text-info"
                                                                href="<?= base_url('rapat/detail_rapat/' . $b->id_rapat) ?>">
                                                                <i class="fa-solid fa-info me-1"></i> Detail
                                                            </a>
                                                        </li>
                                                        <?php if ($can_create): ?>
                                                        <li>
                                                            <a class="dropdown-item text-warning"
                                                                href="<?= base_url('rapat/edit_rapat/' . $b->id_rapat) ?>">
                                                                <i class="fa-solid fa-pen-to-square me-1"></i> Edit
                                                            </a>
                                                        </li>

                                                        <li>
                                                            <a class="dropdown-item text-danger"
                                                                href="<?= base_url('rapat/delete_rapat/' . $b->id_rapat) ?>"
                                                                onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                                <i class="fa-solid fa-trash me-1"></i> Hapus
                                                            </a>
                                                        </li>
                                                        <?php endif; ?>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                            </table>
                            <!-- Modal Pilih Peserta -->
                            <div class="modal fade" id="modalPeserta" tabindex="-1" aria-labelledby="modalPesertaLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalPesertaLabel">Pilih Peserta Rapat</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form id="formPeserta">
                                                <input type="hidden" name="id_rapat" id="id_rapat">
                                                <div id="list-peserta">
                                                    <!-- list user akan dimuat lewat AJAX -->
                                                    <p class="text-muted">Memuat data peserta...</p>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
        </div>

        <script>
            $(document).ready(function () {
                $('#table-rapat').DataTable({
                });
            });
            $(document).ready(function () {
                // Klik tombol Pilih
                $('.btn-pilih-peserta').on('click', function () {
                    let idRapat = $(this).data('id');
                    $('#id_rapat').val(idRapat);

                    $('#list-peserta').html('<p class="text-muted">Memuat data...</p>');

                    $.get('<?= base_url('rapat/get_peserta/') ?>' + idRapat, function (data) {
                        $('#list-peserta').html(data);

                        $('#tablePeserta input[type=checkbox]').on('change', function () {
                            let idUser = $(this).val();
                            let idRapat = $('#id_rapat').val();
                            let checked = $(this).is(':checked') ? 1 : 0;

                            $.ajax({
                                url: '<?= base_url('rapat/simpan_peserta_single') ?>', // endpoint baru untuk update 1 user
                                type: 'POST',
                                data: {
                                    id_rapat: idRapat,
                                    id_user: idUser,
                                    status: checked
                                },
                                dataType: 'json',
                                success: function (res) {
                                    // Opsional: kasih feedback kecil
                                    console.log(res.message);
                                },
                                error: function () {
                                    alert('Gagal menyimpan perubahan peserta');
                                }
                            });
                        });

                        // Event filter divisi
                        $('#filterDivisi').on('change', function () {
                            let val = $(this).val().toLowerCase();
                            $('#tablePeserta tbody tr').filter(function () {
                                let divisi = $(this).find('td:eq(3)').text().toLowerCase();
                                $(this).toggle(val === '' || divisi === val);
                            });
                        });

                        // Event search nama/email
                        $('#searchNama').on('keyup', function () {
                            let val = $(this).val().toLowerCase();
                            $('#tablePeserta tbody tr').filter(function () {
                                let nama = $(this).find('td:eq(1)').text().toLowerCase();
                                let email = $(this).find('td:eq(2)').text().toLowerCase();
                                $(this).toggle(nama.includes(val) || email.includes(val));
                            });
                        });

                    });

                    $('#modalPeserta').modal('show');
                });

                // Simpan peserta
                $('#formPeserta').on('submit', function (e) {
                    e.preventDefault();
                    $.post('<?= base_url('rapat/simpan_peserta') ?>', $(this).serialize(), function (res) {
                        alert(res.message);
                        $('#modalPeserta').modal('hide');
                    }, 'json');
                });
            });


        </script>