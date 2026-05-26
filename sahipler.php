<?php
require_once 'layout.php';

$hata_mesaji = null;
$basari_mesaji = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'ekle') {
            sahip_ekle($conn, $_POST['ad'], $_POST['soyad'], $_POST['tel'], $_POST['mail'], $_POST['adres']);
            $basari_mesaji = "Sahip başarıyla eklendi.";
        } elseif ($_POST['action'] === 'guncelle') {
            sahip_guncelle($conn, $_POST['id'], $_POST['ad'], $_POST['soyad'], $_POST['tel'], $_POST['mail'], $_POST['adres']);
            $basari_mesaji = "Sahip başarıyla güncellendi.";
        } elseif ($_POST['action'] === 'sil') {
            sahip_sil($conn, $_POST['id']);
            $basari_mesaji = "Sahip silindi.";
        }
    }
}

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
        <h3 class="fw-bold mb-1">Sahipler</h3>
        <p class="text-muted mb-0">Tüm hayvan sahiplerini buradan yönetebilirsiniz.</p>
    </div>
    <button class="btn-primary-custom btn" data-bs-toggle="modal" data-bs-target="#ekleModal">
        <i class="fas fa-plus me-2"></i>Yeni Sahip
    </button>
</div>

<div class="card">
    <div class="card-header d-flex align-items-center">
        <i class="fas fa-users me-2" style="color:#1565c0"></i> Sahip Listesi
        <span class="badge-custom ms-auto"><?= count($sahipler) ?> Kayıt</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ad Soyad</th>
                        <th>Telefon</th>
                        <th>Mail</th>
                        <th>Adres</th>
                        <th>Toplam Borç</th>
                        <th>Toplam Ödeme</th>
                        <th>Kalan Borç</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($sahipler)): ?>
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">
                            <i class="fas fa-inbox fa-2x mb-2 d-block"></i>Henüz sahip kaydı yok.
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($sahipler as $s): ?>
                    <?php
                        $toplam_borc = sahip_toplam_borc($conn, $s['sahip_id']);
                        $kalan_borc  = sahip_net_bakiye($conn, $s['sahip_id']);
                        $toplam_odeme = $toplam_borc - $kalan_borc;
                    ?>
                    <tr>
                        <td><span class="badge-custom">#<?= $s['sahip_id'] ?></span></td>
                        <td><strong><?= htmlspecialchars($s['sahip_ad'] . ' ' . $s['sahip_soyad']) ?></strong></td>
                        <td><i class="fas fa-phone me-1 text-muted"></i><?= htmlspecialchars($s['sahip_tel']) ?></td>
                        <td><i class="fas fa-envelope me-1 text-muted"></i><?= htmlspecialchars($s['sahip_mail']) ?></td>
                        <td><?= htmlspecialchars($s['sahip_adres']) ?></td>
                        <td>
                            <span style="color:#1565c0;font-weight:600;">
                                <?= number_format($toplam_borc, 2) ?> ₺
                            </span>
                        </td>
                        <td>
                            <span style="color:#1a7a45;font-weight:600;">
                                <?= number_format($toplam_odeme, 2) ?> ₺
                            </span>
                        </td>
                        <td>
                            <?php if ($kalan_borc > 0): ?>
                                <span class="badge" style="background:#fde8e8;color:#dc3545;font-size:0.85rem;padding:6px 10px;">
                                    <i class="fas fa-exclamation-circle me-1"></i><?= number_format($kalan_borc, 2) ?> ₺
                                </span>
                            <?php else: ?>
                                <span class="badge" style="background:#e8f5ee;color:#1a7a45;font-size:0.85rem;padding:6px 10px;">
                                    <i class="fas fa-check-circle me-1"></i>Kapandı
                                </span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <button class="btn-edit btn btn-sm me-1"
                                data-bs-toggle="modal" data-bs-target="#guncelleModal"
                                onclick="guncelleAc(
                                    <?= $s['sahip_id'] ?>,
                                    '<?= htmlspecialchars($s['sahip_ad'], ENT_QUOTES) ?>',
                                    '<?= htmlspecialchars($s['sahip_soyad'], ENT_QUOTES) ?>',
                                    '<?= htmlspecialchars($s['sahip_tel'], ENT_QUOTES) ?>',
                                    '<?= htmlspecialchars($s['sahip_mail'], ENT_QUOTES) ?>',
                                    '<?= htmlspecialchars($s['sahip_adres'], ENT_QUOTES) ?>'
                                )">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form method="POST" style="display:inline"
                                onsubmit="return confirm('Silmek istediğinize emin misiniz?')">
                                <input type="hidden" name="action" value="sil">
                                <input type="hidden" name="id" value="<?= $s['sahip_id'] ?>">
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
                <h5 class="modal-title"><i class="fas fa-plus me-2"></i>Yeni Sahip Ekle</h5>
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
                        <div class="col-6">
                            <label class="form-label">Telefon</label>
                            <input type="text" name="tel" class="form-control" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Mail</label>
                            <input type="email" name="mail" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Adres</label>
                            <textarea name="adres" class="form-control" rows="2" required></textarea>
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
                <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Sahip Güncelle</h5>
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
                        <div class="col-6">
                            <label class="form-label">Telefon</label>
                            <input type="text" name="tel" id="g_tel" class="form-control" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Mail</label>
                            <input type="email" name="mail" id="g_mail" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Adres</label>
                            <textarea name="adres" id="g_adres" class="form-control" rows="2" required></textarea>
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
function guncelleAc(id, ad, soyad, tel, mail, adres) {
    document.getElementById('g_id').value = id;
    document.getElementById('g_ad').value = ad;
    document.getElementById('g_soyad').value = soyad;
    document.getElementById('g_tel').value = tel;
    document.getElementById('g_mail').value = mail;
    document.getElementById('g_adres').value = adres;
}
</script>

<?php require_once 'layout_footer.php'; ?>
