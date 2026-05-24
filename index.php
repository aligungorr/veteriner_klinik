<?php require_once 'layout.php'; ?>

<?php $stats = istatistik_getir($conn); ?>

<div class="row mb-4">
    <div class="col-12">
        <h3 class="fw-bold text-dark">Hoş Geldiniz, <span style="color:#0f4c2a"><?= $_SESSION['username'] ?></span> 👋</h3>
        <p class="text-muted">Veteriner Klinik Yönetim Sistemi'ne hoş geldiniz.</p>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card h-100" style="border-left: 4px solid #1a7a45;">
            <div class="card-body d-flex align-items-center">
                <div style="width:55px;height:55px;background:#e8f5ee;border-radius:14px;display:flex;align-items:center;justify-content:center;margin-right:15px;">
                    <i class="fas fa-users fa-lg" style="color:#1a7a45"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size:0.8rem;font-weight:600;">TOPLAM SAHİP</div>
                    <div style="font-size:1.8rem;font-weight:800;color:#0f4c2a;"><?= $stats['sahip'] ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card h-100" style="border-left: 4px solid #0d6efd;">
            <div class="card-body d-flex align-items-center">
                <div style="width:55px;height:55px;background:#e8f0fe;border-radius:14px;display:flex;align-items:center;justify-content:center;margin-right:15px;">
                    <i class="fas fa-user-md fa-lg" style="color:#0d6efd"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size:0.8rem;font-weight:600;">TOPLAM DOKTOR</div>
                    <div style="font-size:1.8rem;font-weight:800;color:#0d6efd;"><?= $stats['doktor'] ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card h-100" style="border-left: 4px solid #fd7e14;">
            <div class="card-body d-flex align-items-center">
                <div style="width:55px;height:55px;background:#fff3e0;border-radius:14px;display:flex;align-items:center;justify-content:center;margin-right:15px;">
                    <i class="fas fa-dog fa-lg" style="color:#fd7e14"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size:0.8rem;font-weight:600;">TOPLAM HAYVAN</div>
                    <div style="font-size:1.8rem;font-weight:800;color:#fd7e14;"><?= $stats['hayvan'] ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card h-100" style="border-left: 4px solid #dc3545;">
            <div class="card-body d-flex align-items-center">
                <div style="width:55px;height:55px;background:#fde8e8;border-radius:14px;display:flex;align-items:center;justify-content:center;margin-right:15px;">
                    <i class="fas fa-stethoscope fa-lg" style="color:#dc3545"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size:0.8rem;font-weight:600;">TOPLAM MUAYENE</div>
                    <div style="font-size:1.8rem;font-weight:800;color:#dc3545;"><?= $stats['muayene'] ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-12"><h5 class="fw-bold mb-3">Hızlı Erişim</h5></div>
    <div class="col-md-4">
        <a href="sahipler.php" class="text-decoration-none">
            <div class="card p-3" style="transition:all 0.3s;cursor:pointer;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                <div class="d-flex align-items-center">
                    <div style="width:50px;height:50px;background:#e8f5ee;border-radius:12px;display:flex;align-items:center;justify-content:center;margin-right:15px;">
                        <i class="fas fa-users" style="color:#1a7a45;font-size:1.2rem"></i>
                    </div>
                    <div>
                        <div class="fw-bold" style="color:#0f4c2a">Sahipler</div>
                        <div class="text-muted" style="font-size:0.8rem">Hayvan sahiplerini yönet</div>
                    </div>
                    <i class="fas fa-chevron-right ms-auto text-muted"></i>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="doktorlar.php" class="text-decoration-none">
            <div class="card p-3" style="transition:all 0.3s;cursor:pointer;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                <div class="d-flex align-items-center">
                    <div style="width:50px;height:50px;background:#e8f0fe;border-radius:12px;display:flex;align-items:center;justify-content:center;margin-right:15px;">
                        <i class="fas fa-user-md" style="color:#0d6efd;font-size:1.2rem"></i>
                    </div>
                    <div>
                        <div class="fw-bold" style="color:#0d6efd">Doktorlar</div>
                        <div class="text-muted" style="font-size:0.8rem">Veteriner doktorları yönet</div>
                    </div>
                    <i class="fas fa-chevron-right ms-auto text-muted"></i>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="hayvanlar.php" class="text-decoration-none">
            <div class="card p-3" style="transition:all 0.3s;cursor:pointer;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                <div class="d-flex align-items-center">
                    <div style="width:50px;height:50px;background:#fff3e0;border-radius:12px;display:flex;align-items:center;justify-content:center;margin-right:15px;">
                        <i class="fas fa-dog" style="color:#fd7e14;font-size:1.2rem"></i>
                    </div>
                    <div>
                        <div class="fw-bold" style="color:#fd7e14">Hayvanlar</div>
                        <div class="text-muted" style="font-size:0.8rem">Kayıtlı hayvanları yönet</div>
                    </div>
                    <i class="fas fa-chevron-right ms-auto text-muted"></i>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="muayeneler.php" class="text-decoration-none">
            <div class="card p-3" style="transition:all 0.3s;cursor:pointer;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                <div class="d-flex align-items-center">
                    <div style="width:50px;height:50px;background:#fde8e8;border-radius:12px;display:flex;align-items:center;justify-content:center;margin-right:15px;">
                        <i class="fas fa-stethoscope" style="color:#dc3545;font-size:1.2rem"></i>
                    </div>
                    <div>
                        <div class="fw-bold" style="color:#dc3545">Muayeneler</div>
                        <div class="text-muted" style="font-size:0.8rem">Muayene kayıtlarını yönet</div>
                    </div>
                    <i class="fas fa-chevron-right ms-auto text-muted"></i>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="odemeler.php" class="text-decoration-none">
            <div class="card p-3" style="transition:all 0.3s;cursor:pointer;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                <div class="d-flex align-items-center">
                    <div style="width:50px;height:50px;background:#f3e8ff;border-radius:12px;display:flex;align-items:center;justify-content:center;margin-right:15px;">
                        <i class="fas fa-credit-card" style="color:#6f42c1;font-size:1.2rem"></i>
                    </div>
                    <div>
                        <div class="fw-bold" style="color:#6f42c1">Ödemeler</div>
                        <div class="text-muted" style="font-size:0.8rem">Ödeme kayıtlarını yönet</div>
                    </div>
                    <i class="fas fa-chevron-right ms-auto text-muted"></i>
                </div>
            </div>
        </a>
    </div>
</div>

<?php require_once 'layout_footer.php'; ?>