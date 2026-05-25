<?php
require_once 'layout.php';

$hata_mesaji = null;
$basari_mesaji = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'ekle') {
            $result = hayvan_ekle($conn, $_POST['sahip_id'], $_POST['ad'], $_POST['tur'], $_POST['irk'], $_POST['dogum'], $_POST['cinsiyet']);
            if ($result === false && isset($_SESSION['hata'])) {
                $hata_mesaji = $_SESSION['hata'];
                unset($_SESSION['hata']);
            } else {
                $basari_mesaji = "Hayvan başarıyla eklendi.";
            }
        } elseif ($_POST['action'] === 'guncelle') {
            $result = hayvan_guncelle($conn, $_POST['id'], $_POST['ad'], $_POST['tur'], $_POST['irk'], $_POST['dogum'], $_POST['cinsiyet']);
            if ($result === false && isset($_SESSION['hata'])) {
                $hata_mesaji = $_SESSION['hata'];
                unset($_SESSION['hata']);
            } else {
                $basari_mesaji = "Hayvan başarıyla güncellendi.";
            }
        } elseif ($_POST['action'] === 'sil') {
            hayvan_sil($conn, $_POST['id']);
            $basari_mesaji = "Hayvan silindi.";
        }
    }
}

$hayvanlar = hayvan_listele($conn);
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
        <h3 class="fw-bold mb-1">Hayvanlar</h3>
        <p class="text-muted mb-0">Tüm kayıtlı hayvanları buradan yönetebilirsiniz.</p>
    </div>
    <button class="btn-primary-custom btn" data-bs-toggle="modal" data-bs-target="#ekleModal">
        <i class="fas fa-plus me-2"></i>Yeni Hayvan
    </button>
</div>

<div class="card">
    <div class="card-header d-flex align-items-center">
        <i class="fas fa-dog me-2" style="color:#1565c0"></i> Hayvan Listesi
        <span class="badge-custom ms-auto"><?= count($hayvanlar) ?> Kayıt</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Hayvan Adı</th>
                        <th>Sahip</th>
                        <th>Tür</th>
                        <th>Irk</th>
                        <th>Doğum</th>
                        <th>Cinsiyet</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($hayvanlar)): ?>
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">
                            <i class="fas fa-inbox fa-2x mb-2 d-block"></i>Henüz hayvan kaydı yok.
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($hayvanlar as $h): ?>
                    <tr>
                        <td><span class="badge-custom">#<?= $h['hayvan_id'] ?></span></td>
                        <td><strong><?= htmlspecialchars($h['hayvan_ad']) ?></strong></td>
                        <td><?= htmlspecialchars($h['sahip_adi']) ?></td>
                        <td><span class="badge bg-light text-dark"><?= htmlspecialchars($h['hayvan_tur']) ?></span></td>
                        <td><?= htmlspecialchars($h['hayvan_irk']) ?></td>
                        <td><?= htmlspecialchars($h['hayvan_dogum']) ?></td>
                        <td>
                            <?php if ($h['hayvan_cinsiyet'] === 'Erkek'): ?>
                            <span class="badge" style="background:#e8f0fe;color:#0d6efd">♂ Erkek</span>
                            <?php else: ?>
                            <span class="badge" style="background:#fde8f0;color:#e83e8c">♀ Dişi</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <button class="btn-edit btn btn-sm me-1"
                                data-bs-toggle="modal" data-bs-target="#guncelleModal"
                                onclick="guncelleAc(
                                    <?= $h['hayvan_id'] ?>,
                                    '<?= htmlspecialchars($h['hayvan_ad'], ENT_QUOTES) ?>',
                                    '<?= htmlspecialchars($h['hayvan_tur'], ENT_QUOTES) ?>',
                                    '<?= htmlspecialchars($h['hayvan_irk'], ENT_QUOTES) ?>',
                                    '<?= htmlspecialchars($h['hayvan_dogum'], ENT_QUOTES) ?>',
                                    '<?= htmlspecialchars($h['hayvan_cinsiyet'], ENT_QUOTES) ?>'
                                )">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form method="POST" style="display:inline"
                                onsubmit="return confirm('Silmek istediğinize emin misiniz?')">
                                <input type="hidden" name="action" value="sil">
                                <input type="hidden" name="id" value="<?= $h['hayvan_id'] ?>">
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
                <h5 class="modal-title"><i class="fas fa-plus me-2"></i>Yeni Hayvan Ekle</h5>
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
                        <div class="col-12">
                            <label class="form-label">Hayvan Adı</label>
                            <input type="text" name="ad" class="form-control" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Tür</label>
                            <input type="text" name="tur" class="form-control" placeholder="Kedi, Köpek..." required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Irk</label>
                            <input type="text" name="irk" class="form-control" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Doğum Tarihi</label>
                            <input type="date" name="dogum" class="form-control" max="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Cinsiyet</label>
                            <select name="cinsiyet" class="form-select" required>
                                <option value="Erkek">♂ Erkek</option>
                                <option value="Dişi">♀ Dişi</option>
                            </select>
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
                <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Hayvan Güncelle</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <input type="hidden" name="action" value="guncelle">
                <div class="modal-body">
                    <input type="hidden" name="id" id="g_id">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Hayvan Adı</label>
                            <input type="text" name="ad" id="g_ad" class="form-control" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Tür</label>
                            <input type="text" name="tur" id="g_tur" class="form-control" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Irk</label>
                            <input type="text" name="irk" id="g_irk" class="form-control" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Doğum Tarihi</label>
                            <input type="date" name="dogum" id="g_dogum" class="form-control" max="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Cinsiyet</label>
                            <select name="cinsiyet" id="g_cinsiyet" class="form-select" required>
                                <option value="Erkek">♂ Erkek</option>
                                <option value="Dişi">♀ Dişi</option>
                            </select>
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
function guncelleAc(id, ad, tur, irk, dogum, cinsiyet) {
    document.getElementById('g_id').value = id;
    document.getElementById('g_ad').value = ad;
    document.getElementById('g_tur').value = tur;
    document.getElementById('g_irk').value = irk;
    document.getElementById('g_dogum').value = dogum;
    document.getElementById('g_cinsiyet').value = cinsiyet;
}
</script>

<?php require_once 'layout_footer.php'; ?>
