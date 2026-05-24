<?php
require_once 'layout.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'ekle') {
            muayene_ekle($conn, $_POST['hayvan_id'], $_POST['doktor_id'], $_POST['tani'], $_POST['tedavi'], $_POST['ucret']);
        } elseif ($_POST['action'] === 'guncelle') {
            muayene_guncelle($conn, $_POST['id'], $_POST['tani'], $_POST['tedavi'], $_POST['ucret']);
        } elseif ($_POST['action'] === 'sil') {
            muayene_sil($conn, $_POST['id']);
        }
    }
}

$muayeneler = muayene_listele($conn);
$hayvanlar = hayvan_listele($conn);
$doktorlar = doktor_listele($conn);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Muayeneler</h3>
        <p class="text-muted mb-0">Tüm muayene kayıtlarını buradan yönetebilirsiniz.</p>
    </div>
    <button class="btn-primary-custom btn" data-bs-toggle="modal" data-bs-target="#ekleModal">
        <i class="fas fa-plus me-2"></i>Yeni Muayene
    </button>
</div>

<div class="card">
    <div class="card-header d-flex align-items-center">
        <i class="fas fa-stethoscope me-2" style="color:#1565c0"></i> Muayene Listesi
        <span class="badge-custom ms-auto"><?= count($muayeneler) ?> Kayıt</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Hayvan</th>
                        <th>Doktor</th>
                        <th>Tarih</th>
                        <th>Tanı</th>
                        <th>Tedavi</th>
                        <th>Ücret</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($muayeneler)): ?>
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">
                            <i class="fas fa-inbox fa-2x mb-2 d-block"></i>Henüz muayene kaydı yok.
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($muayeneler as $m): ?>
                    <tr>
                        <td><span class="badge-custom">#<?= $m['muayene_id'] ?></span></td>
                        <td><strong><?= htmlspecialchars($m['hayvan_ad']) ?></strong></td>
                        <td>Dr. <?= htmlspecialchars($m['doktor_adi']) ?></td>
                        <td><?= htmlspecialchars($m['muayene_tarih']) ?></td>
                        <td><?= htmlspecialchars($m['muayene_tani']) ?></td>
                        <td><?= htmlspecialchars($m['muayene_tedavi']) ?></td>
                        <td><strong style="color:#1565c0"><?= htmlspecialchars($m['muayene_ucret']) ?> ₺</strong></td>
                        <td>
                            <button class="btn-edit btn btn-sm me-1"
                                data-bs-toggle="modal" data-bs-target="#guncelleModal"
                                onclick="guncelleAc(
                                    <?= $m['muayene_id'] ?>,
                                    '<?= htmlspecialchars($m['muayene_tani'], ENT_QUOTES) ?>',
                                    '<?= htmlspecialchars($m['muayene_tedavi'], ENT_QUOTES) ?>',
                                    <?= $m['muayene_ucret'] ?>
                                )">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form method="POST" style="display:inline"
                                onsubmit="return confirm('Silmek istediğinize emin misiniz?')">
                                <input type="hidden" name="action" value="sil">
                                <input type="hidden" name="id" value="<?= $m['muayene_id'] ?>">
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
                <h5 class="modal-title"><i class="fas fa-plus me-2"></i>Yeni Muayene Ekle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <input type="hidden" name="action" value="ekle">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label">Hayvan</label>
                            <select name="hayvan_id" class="form-select" required>
                                <option value="">Hayvan Seçin</option>
                                <?php foreach ($hayvanlar as $h): ?>
                                <option value="<?= $h['hayvan_id'] ?>"><?= htmlspecialchars($h['hayvan_ad']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Doktor</label>
                            <select name="doktor_id" class="form-select" required>
                                <option value="">Doktor Seçin</option>
                                <?php foreach ($doktorlar as $d): ?>
                                <option value="<?= $d['doktor_id'] ?>">Dr. <?= htmlspecialchars($d['doktor_ad'] . ' ' . $d['doktor_soyad']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Tanı</label>
                            <input type="text" name="tani" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Tedavi</label>
                            <input type="text" name="tedavi" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Ücret (₺)</label>
                            <input type="number" name="ucret" step="0.01" min="0" class="form-control" required>
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
                <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Muayene Güncelle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <input type="hidden" name="action" value="guncelle">
                <div class="modal-body">
                    <input type="hidden" name="id" id="g_id">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Tanı</label>
                            <input type="text" name="tani" id="g_tani" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Tedavi</label>
                            <input type="text" name="tedavi" id="g_tedavi" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Ücret (₺)</label>
                            <input type="number" name="ucret" id="g_ucret" step="0.01" min="0" class="form-control" required>
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
function guncelleAc(id, tani, tedavi, ucret) {
    document.getElementById('g_id').value = id;
    document.getElementById('g_tani').value = tani;
    document.getElementById('g_tedavi').value = tedavi;
    document.getElementById('g_ucret').value = ucret;
}
</script>

<?php require_once 'layout_footer.php'; ?>
