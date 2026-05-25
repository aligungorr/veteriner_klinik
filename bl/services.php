<?php
require_once __DIR__ . '/../dal/db.php';

// ===== SAHİPLER =====
function sahip_listele($conn) {
    return call_procedure($conn, 'sp_SahipListele');
}
function sahip_ekle($conn, $ad, $soyad, $tel, $mail, $adres) {
    return call_procedure($conn, 'sp_SahipEkle', [$ad, $soyad, $tel, $mail, $adres]);
}
function sahip_guncelle($conn, $id, $ad, $soyad, $tel, $mail, $adres) {
    return call_procedure($conn, 'sp_SahipGuncelle', [$id, $ad, $soyad, $tel, $mail, $adres]);
}
function sahip_sil($conn, $id) {
    return call_procedure($conn, 'sp_SahipSil', [$id]);
}

// ===== DOKTORLAR =====
function doktor_listele($conn) {
    return call_procedure($conn, 'sp_DoktorListele');
}
function doktor_ekle($conn, $ad, $soyad, $uzmanlik, $tel) {
    return call_procedure($conn, 'sp_DoktorEkle', [$ad, $soyad, $uzmanlik, $tel]);
}
function doktor_guncelle($conn, $id, $ad, $soyad, $uzmanlik, $tel) {
    return call_procedure($conn, 'sp_DoktorGuncelle', [$id, $ad, $soyad, $uzmanlik, $tel]);
}
function doktor_sil($conn, $id) {
    return call_procedure($conn, 'sp_DoktorSil', [$id]);
}

// ===== HAYVANLAR =====
function hayvan_listele($conn) {
    return call_procedure($conn, 'sp_HayvanListele');
}
function hayvan_ekle($conn, $sahip_id, $ad, $tur, $irk, $dogum, $cinsiyet) {
    return call_procedure($conn, 'sp_HayvanEkle', [$sahip_id, $ad, $tur, $irk, $dogum, $cinsiyet]);
}
function hayvan_guncelle($conn, $id, $ad, $tur, $irk, $dogum, $cinsiyet) {
    return call_procedure($conn, 'sp_HayvanGuncelle', [$id, $ad, $tur, $irk, $dogum, $cinsiyet]);
}
function hayvan_sil($conn, $id) {
    return call_procedure($conn, 'sp_HayvanSil', [$id]);
}

// ===== MUAYENELER =====
function muayene_listele($conn) {
    return call_procedure($conn, 'sp_MuayeneListele');
}
function muayene_ekle($conn, $hayvan_id, $doktor_id, $tani, $tedavi, $ucret) {
    return call_procedure($conn, 'sp_MuayeneEkle', [$hayvan_id, $doktor_id, $tani, $tedavi, $ucret]);
}
function muayene_guncelle($conn, $id, $tani, $tedavi, $ucret) {
    return call_procedure($conn, 'sp_MuayeneGuncelle', [$id, $tani, $tedavi, $ucret]);
}
function muayene_sil($conn, $id) {
    return call_procedure($conn, 'sp_MuayeneSil', [$id]);
}

// ===== ÖDEMELER =====
function odeme_listele($conn) {
    return call_procedure($conn, 'sp_OdemeListele');
}
function odeme_ekle($conn, $sahip_id, $tutar, $tur, $aciklama) {
    return call_procedure($conn, 'sp_OdemeEkle', [$sahip_id, $tutar, $tur, $aciklama]);
}
function odeme_guncelle($conn, $id, $tutar, $tur, $aciklama) {
    return call_procedure($conn, 'sp_OdemeGuncelle', [$id, $tutar, $tur, $aciklama]);
}
function odeme_sil($conn, $id) {
    return call_procedure($conn, 'sp_OdemeSil', [$id]);
}

// ===== KULLANICI =====
function kullanici_getir($conn, $ad) {
    $result = call_procedure($conn, 'sp_KullaniciGetir', [$ad]);
    return !empty($result) ? $result[0] : null;
}

// ===== FUNCTIONS (MySQL fonksiyonlarını çağır) =====
function sahip_toplam_borc($conn, $sahip_id) {
    $result = mysqli_query($conn, "SELECT fn_SahipToplamBorc($sahip_id) as toplam");
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row['toplam'] ?? 0;
    }
    return 0;
}

function hayvan_muayene_sayisi($conn, $hayvan_id) {
    $result = mysqli_query($conn, "SELECT fn_HayvanMuayeneSayisi($hayvan_id) as sayi");
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row['sayi'] ?? 0;
    }
    return 0;
}

// ===== İSTATİSTİKLER =====
function istatistik_getir($conn) {
    return [
        'sahip'   => count(sahip_listele($conn)),
        'doktor'  => count(doktor_listele($conn)),
        'hayvan'  => count(hayvan_listele($conn)),
        'muayene' => count(muayene_listele($conn)),
    ];
}
?>
