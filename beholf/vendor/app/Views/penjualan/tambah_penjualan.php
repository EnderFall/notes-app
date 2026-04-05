<div id="main-content">
  <div class="page-heading">
    <div class="page-title">
      <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
          <h3>Input <?=$title?></h3>
          <p class="text-subtitle text-muted">
            Silakan Masukkan <?=$title?>
          </p>
        </div>
        <div class="col-12 col-md-6 order-md-2 order-first">
          <nav
          aria-label="breadcrumb"
          class="breadcrumb-header float-start float-lg-end"
          >
          <ol class="breadcrumb">
            <li class="breadcrumb-item">
              <a href="<?=base_url('admin')?>">Dashboard</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
              Input <?=$title?>
            </li>
          </ol>
        </nav>
      </div>
    </div>
  </div>
  <section id="basic-horizontal-layouts">
    <div class="row match-height">
      <div class="col-12">
        <div class="card">
          <div class="card-content">
            <div class="card-body">
              <form class="form-horizontal form-label-left" novalidate action="<?= base_url('penjualan/simpan_penjualan')?>" method="post" enctype="multipart/form-data">
                <form class="form form-horizontal">
                  <div class="form-body">

                    <div class="row">
                        <!-- Kolom kiri -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="id_mobil" class="form-label">Mobil</label>
                                <select name="id_mobil" id="id_mobil" class="form-select" required>
                                    <option value="">-- Pilih Mobil --</option>
                                    <?php foreach ($mobil as $m): ?>
                                        <option value="<?= $m->id_mobil ?>">
                                            <?= $m->nama_mobil ?> - <?= $m->kode_mobil ?> (<?= $m->plat_mobil ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="id_pelanggan" class="form-label">Pelanggan</label>
                                <select name="id_pelanggan" id="id_pelanggan" class="form-select" required>
                                    <option value="">-- Pilih Pelanggan --</option>
                                    <?php foreach ($pelanggan as $p): ?>
                                        <option value="<?= $p->id_pelanggan ?>"><?= $p->nama_pelanggan ?> - <?= $p->no_hp ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="harga" class="form-label">Harga Jual</label>
                                <input type="text" name="harga" id="harga" class="form-control" required>
                            </div>
                        </div>

                        <!-- Kolom kanan -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="catatan" class="form-label">Catatan</label>
                                  <textarea name="catatan" id="catatan" class="form-control" rows="3"></textarea>
                              </div>

                            <div class="mb-3">
                                <label for="tanggal_jual" class="form-label">Tanggal Penjualan</label>
                              <input type="date" name="tanggal_jual" id="tanggal_jual" class="form-control" value="<?= date('Y-m-d') ?>" required>
                          </div>
                        </div>
                    </div>

                      <div class="col-sm-12 d-flex justify-content-end">
                        <button
                        type="submit"
                        class="btn btn-primary me-1 mb-1"
                        >
                        Submit
                      </button>
                      <button
                      type="reset"
                      class="btn btn-light-secondary me-1 mb-1"
                      >
                      Reset
                    </button>
                  </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

<script>
    const hargaInput = document.getElementById("harga");

    hargaInput.addEventListener("input", function () {
        let value = this.value.replace(/[^,\d]/g, '').toString();
        let split = value.split(',');
        let sisa = split[0].length % 3;
        let rupiah = split[0].substr(0, sisa);
        let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            let separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
        this.value = 'Rp ' + rupiah;
    });
</script>
