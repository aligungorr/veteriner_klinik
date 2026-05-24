<?php
require_once 'layout.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'ekle') {
            doktor_ekle($conn, $_POST['ad'], $_POST['soyad'], $_POST['uzmanlik'], $_POST['tel']);
        } elseif ($_POST['action'] === 'guncelle') {
            doktor_guncelle($conn, $_POST['id'], $_POST['ad'], $_POST['soyad'], $_POST['uzmanlik'], $_POST['tel']);
        } elseif ($_POST['action'] === 'sil') {
            doktor_sil($conn, $_POST['id']);
        }
    }
}

$doktorlar = doktor_listele($conn);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Doktorlar</h3>
        <p class="text-muted mb-0">Tüm veteriner doktorlarını buradan yönetebilirsiniz.</p>
    </div>
    <button class="btn-primary-custom btn" data-bs-toggle="modal" data-bs-target="#ekleModal">
        <i class="fas fa-plus me-2"></i>Yeni Doktor
    </button>
</div>

<div class="card">
    <div class="card-header d-flex align-items-center">
        <i class="fas fa-user-md me-2" style="color:#1565c0"></i> Doktor Listesi
        <span class="badge-custom ms-auto"><?= count($doktorlar) ?> Kayıt</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ad Soyad</th>
                        <th>Uzmanlık</th>
                        <th>Telefon</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($doktorlar)): ?>
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">
                            <i class="fas fa-inbox fa-2x mb-2 d-block"></i>Henüz doktor kaydı yok.
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($doktorlar as $d): ?>
                    <tr>
                        <td><span class="badge-custom">#<?= $d['doktor_id'] ?></span></td>
                        <td><strong>Dr. <?= htmlspecialchars($d['doktor_ad'] . ' ' . $d['doktor_soyad']) ?></strong></td>
                        <td><span class="badge bg-light text-dark"><?= htmlspecialchars($d['doktor_uzmanlik']) ?></span></td>
                        <td><i class="fas fa-phone me-1 text-muted"></i><?= htmlspecialchars($d['doktor_tel']) ?></td>
                        <td>
                            <button class="btn-edit btn btn-sm me-1"
                                data-bs-toggle="modal" data-bs-target="#guncelleModal"
                                onclick="guncelleAc(
                                    <?= $d['doktor_id'] ?>,
                                    '<?= htmlspecialchars($d['doktor_ad'], ENT_QUOTES) ?>',
                                    '<?= htmlspecialchars($d['doktor_soyad'], ENT_QUOTES) ?>',
                                    '<?= htmlspecialchars($d['doktor_uzmanlik'], ENT_QUOTES) ?>',
                                    '<?= htmlspecialchars($d['doktor_tel'], ENT_QUOTES) ?>'
                                )">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form method="POST" style="display:inline"
                                onsubmit="return confirm('Silmek istediğinize emin misiniz?')">
                                <input type="hidden" name="action" value="sil">
                                <input type="hidden" name="id" value="<?= $d['doktor_id'] ?>">
                                <button type="submit" class="btn-delete btn btn-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Ekle Modal -->
<div class="modal fade" id="ekleModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-plus me-2"></i>Yeni Doktor Ekle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <input type="hidden" name="action" value="ekle">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label">Ad</label>
                            <input type="text" name="ad" class="form-control" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Soyad</label>
                            <input type="text" name="soyad" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Uzmanlık</label>
                            <input type="text" name="uzmanlik" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Telefon</label>
                            <input type="text" name="tel" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    <button type="submit" class="btn-save btn">Kaydet</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Güncelle Modal -->
<div class="modal fade" id="guncelleModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Doktor Güncelle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <input type="hidden" name="action" value="guncelle">
                <div class="modal-body">
                    <input type="hidden" name="id" id="g_id">
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label">Ad</label>
                            <input type="text" name="ad" id="g_ad" class="form-control" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Soyad</label>
                            <input type="text" name="soyad" id="g_soyad" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Uzmanlık</label>
                            <input type="text" name="uzmanlik" id="g_uzmanlik" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Telefon</label>
                            <input type="text" name="tel" id="g_tel" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    <button type="submit" class="btn-update btn">Güncelle</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function guncelleAc(id, ad, soyad, uzmanlik, tel) {
    document.getElementById('g_id').value = id;
    document.getElementById('g_ad').value = ad;
    document.getElementById('g_soyad').value = soyad;
    document.getElementById('g_uzmanlik').value = uzmanlik;
    document.getElementById('g_tel').value = tel;
}
</script>

<?php require_once 'layout_footer.php'; ?>
