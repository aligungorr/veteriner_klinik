<?php
require_once 'layout.php';

$hata_mesaji = null;
$basari_mesaji = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'ekle') {
            $result = muayene_ekle($conn, $_POST['hayvan_id'], $_POST['doktor_id'], $_POST['tani'], $_POST['tedavi'], $_POST['ucret']);
            if ($result === false && isset($_SESSION['hata'])) {
                $hata_mesaji = $_SESSION['hata'];
                unset($_SESSION['hata']);
            } else {
                $basari_mesaji = "Muayene başarıyla eklendi.";
            }
        } elseif ($_POST['action'] === 'guncelle') {
            $result = muayene_guncelle($conn, $_POST['id'], $_POST['tani'], $_POST['tedavi'], $_POST['ucret']);
            if ($result === false && isset($_SESSION['hata'])) {
                $hata_mesaji = $_SESSION['hata'];
                unset($_SESSION['hata']);
            } else {
                $basari_mesaji = "Muayene başarıyla güncellendi.";
            }
        } elseif ($_POST['action'] === 'sil') {
            muayene_sil($conn, $_POST['id']);
            $basari_mesaji = "Muayene silindi.";
        } elseif ($_POST['action'] === 'odeme_al') {
            // Muayeneden gelen bilgiyle otomatik ödeme oluştur
            odeme_ekle($conn, $_POST['sahip_id'], $_POST['tutar'], $_POST['tur'], 'Muayene ID: ' . $_POST['muayene_id'] . ' için ödeme');
            $basari_mesaji = "Ödeme başarıyla alındı.";
        }
    }
}

$muayeneler = muayene_listele($conn);
$hayvanlar = hayvan_listele($conn);
$doktorlar = doktor_listele($conn);
$sahipler = sahip_listele($conn);
?>

<?php if ($hata_mesaji): ?>
<div class="alert alert-danger alert-dismissible fade show mx-0 mb-3" role="alert">
    <i class="fas fa-exclamation-triangle me-2"></i>
    <strong>Hata:</strong> <?= htmlspecialchars($hata_mesaji) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<?php if ($basari_mesaji): ?>
<div class="alert alert-success alert-dismissible fade show mx-0 mb-3" role="alert">
    <i class="fas fa-check-circle me-2"></i>
    <?= htmlspecialchars($basari_mesaji) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

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
                    <?php
                        // Hayvanın sahibini bul
                        $sahip_id = null;
                        foreach ($hayvanlar as $h) {
                            if ($h['hayvan_id'] == $m['hayvan_id']) {
                                $sahip_id = $h['sahip_id'];
                                break;
                            }
                        }
                    ?>
                    <tr>
                        <td><span class="badge-custom">#<?= $m['muayene_id'] ?></span></td>
                        <td><strong><?= htmlspecialchars($m['hayvan_ad']) ?></strong></td>
                        <td>Dr. <?= htmlspecialchars($m['doktor_adi']) ?></td>
                        <td><?= htmlspecialchars($m['muayene_tarih']) ?></td>
                        <td><?= htmlspecialchars($m['muayene_tani']) ?></td>
                        <td><?= htmlspecialchars($m['muayene_tedavi']) ?></td>
                        <td><strong style="color:#1565c0"><?= number_format($m['muayene_ucret'], 2) ?> ₺</strong></td>
                        <td>
                            <!-- Ödeme Al butonu -->
                            <button class="btn btn-sm me-1"
                                style="background:#e8f5ee;color:#1a7a45;border:none;border-radius:8px;padding:6px 10px;"
                                data-bs-toggle="modal" data-bs-target="#odemeModal"
                                onclick="odemeAc(<?= $m['muayene_id'] ?>, <?= $sahip_id ?>, <?= $m['muayene_ucret'] ?>)"
                                title="Ödeme Al">
                                <i class="fas fa-money-bill-wave"></i>
                            </button>
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

<!-- Ödeme Al Modal -->
<div class="modal fade" id="odemeModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background:linear-gradient(135deg,#1a7a45,#2e7d32);">
                <h5 class="modal-title text-white"><i class="fas fa-money-bill-wave me-2"></i>Ödeme Al</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <input type="hidden" name="action" value="odeme_al">
                <input type="hidden" name="muayene_id" id="o_muayene_id">
                <input type="hidden" name="sahip_id" id="o_sahip_id">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Tutar (₺)</label>
                            <input type="number" name="tutar" id="o_tutar" step="0.01" min="0" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Ödeme Türü</label>
                            <select name="tur" class="form-select" required>
                                <option value="Nakit">💵 Nakit</option>
                                <option value="Kredi Kartı">💳 Kredi Kartı</option>
                                <option value="Banka Transferi">🏦 Banka Transferi</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    <button type="submit" class="btn" style="background:linear-gradient(135deg,#1a7a45,#2e7d32);color:white;border:none;border-radius:10px;padding:10px 24px;font-weight:600;">
                        <i class="fas fa-check me-2"></i>Ödemeyi Kaydet
                    </button>
                </div>
            </form>
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

function odemeAc(muayene_id, sahip_id, tutar) {
    document.getElementById('o_muayene_id').value = muayene_id;
    document.getElementById('o_sahip_id').value = sahip_id;
    document.getElementById('o_tutar').value = tutar;
}
</script>

<?php require_once 'layout_footer.php'; ?>
