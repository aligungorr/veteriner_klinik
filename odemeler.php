<?php
require_once 'layout.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'ekle') {
            odeme_ekle($conn, $_POST['sahip_id'], $_POST['tutar'], $_POST['tur'], $_POST['aciklama']);
        } elseif ($_POST['action'] === 'guncelle') {
            odeme_guncelle($conn, $_POST['id'], $_POST['tutar'], $_POST['tur'], $_POST['aciklama']);
        } elseif ($_POST['action'] === 'sil') {
            odeme_sil($conn, $_POST['id']);
        }
    }
}

$odemeler = odeme_listele($conn);
$sahipler = sahip_listele($conn);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Ödemeler</h3>
        <p class="text-muted mb-0">Tüm ödeme kayıtlarını buradan yönetebilirsiniz.</p>
    </div>
    <button class="btn-primary-custom btn" data-bs-toggle="modal" data-bs-target="#ekleModal">
        <i class="fas fa-plus me-2"></i>Yeni Ödeme
    </button>
</div>

<div class="card">
    <div class="card-header d-flex align-items-center">
        <i class="fas fa-credit-card me-2" style="color:#1565c0"></i> Ödeme Listesi
        <span class="badge-custom ms-auto"><?= count($odemeler) ?> Kayıt</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Sahip</th>
                        <th>Tarih</th>
                        <th>Tutar</th>
                        <th>Ödeme Türü</th>
                        <th>Açıklama</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($odemeler)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="fas fa-inbox fa-2x mb-2 d-block"></i>Henüz ödeme kaydı yok.
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($odemeler as $o): ?>
                    <tr>
                        <td><span class="badge-custom">#<?= $o['odeme_id'] ?></span></td>
                        <td><strong><?= htmlspecialchars($o['sahip_adi']) ?></strong></td>
                        <td><?= htmlspecialchars($o['odeme_tarih']) ?></td>
                        <td><strong style="color:#1565c0"><?= htmlspecialchars($o['odeme_tutar']) ?> ₺</strong></td>
                        <td>
                            <?php if ($o['odeme_tur'] === 'Nakit'): ?>
                            <span class="badge" style="background:#e8f5ee;color:#1a7a45">💵 Nakit</span>
                            <?php elseif ($o['odeme_tur'] === 'Kredi Kartı'): ?>
                            <span class="badge" style="background:#e8f0fe;color:#0d6efd">💳 Kredi Kartı</span>
                            <?php else: ?>
                            <span class="badge" style="background:#fff3e0;color:#fd7e14">🏦 Banka Transferi</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($o['odeme_aciklama'] ?? '') ?></td>
                        <td>
                            <button class="btn-edit btn btn-sm me-1"
                                data-bs-toggle="modal" data-bs-target="#guncelleModal"
                                onclick="guncelleAc(
                                    <?= $o['odeme_id'] ?>,
                                    <?= $o['odeme_tutar'] ?>,
                                    '<?= htmlspecialchars($o['odeme_tur'], ENT_QUOTES) ?>',
                                    '<?= htmlspecialchars($o['odeme_aciklama'] ?? '', ENT_QUOTES) ?>'
                                )">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form method="POST" style="display:inline"
                                onsubmit="return confirm('Silmek istediğinize emin misiniz?')">
                                <input type="hidden" name="action" value="sil">
                                <input type="hidden" name="id" value="<?= $o['odeme_id'] ?>">
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
                <h5 class="modal-title"><i class="fas fa-plus me-2"></i>Yeni Ödeme Ekle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <input type="hidden" name="action" value="ekle">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Sahip</label>
                            <select name="sahip_id" class="form-select" required>
                                <option value="">Sahip Seçin</option>
                                <?php foreach ($sahipler as $s): ?>
                                <option value="<?= $s['sahip_id'] ?>"><?= htmlspecialchars($s['sahip_ad'] . ' ' . $s['sahip_soyad']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Tutar (₺)</label>
                            <input type="number" name="tutar" step="0.01" min="0" class="form-control" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Ödeme Türü</label>
                            <select name="tur" class="form-select" required>
                                <option value="Nakit">💵 Nakit</option>
                                <option value="Kredi Kartı">💳 Kredi Kartı</option>
                                <option value="Banka Transferi">🏦 Banka Transferi</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Açıklama</label>
                            <textarea name="aciklama" class="form-control" rows="2"></textarea>
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
                <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Ödeme Güncelle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <input type="hidden" name="action" value="guncelle">
                <div class="modal-body">
                    <input type="hidden" name="id" id="g_id">
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label">Tutar (₺)</label>
                            <input type="number" name="tutar" id="g_tutar" step="0.01" min="0" class="form-control" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Ödeme Türü</label>
                            <select name="tur" id="g_tur" class="form-select" required>
                                <option value="Nakit">💵 Nakit</option>
                                <option value="Kredi Kartı">💳 Kredi Kartı</option>
                                <option value="Banka Transferi">🏦 Banka Transferi</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Açıklama</label>
                            <textarea name="aciklama" id="g_aciklama" class="form-control" rows="2"></textarea>
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
function guncelleAc(id, tutar, tur, aciklama) {
    document.getElementById('g_id').value = id;
    document.getElementById('g_tutar').value = tutar;
    document.getElementById('g_tur').value = tur;
    document.getElementById('g_aciklama').value = aciklama;
}
</script>

<?php require_once 'layout_footer.php'; ?>
