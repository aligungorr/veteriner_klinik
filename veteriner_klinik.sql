DROP DATABASE IF EXISTS veteriner_klinik;
CREATE DATABASE veteriner_klinik;
USE veteriner_klinik;

-- ============================================
-- TABLOLAR
-- ============================================

CREATE TABLE sahipler (
    sahip_id INT AUTO_INCREMENT PRIMARY KEY,
    sahip_ad VARCHAR(64) NOT NULL,
    sahip_soyad VARCHAR(64) NOT NULL,
    sahip_tel VARCHAR(20) NOT NULL UNIQUE,
    sahip_mail VARCHAR(100) NOT NULL UNIQUE,
    sahip_adres VARCHAR(250) NOT NULL
);

CREATE TABLE doktorlar (
    doktor_id INT AUTO_INCREMENT PRIMARY KEY,
    doktor_ad VARCHAR(64) NOT NULL,
    doktor_soyad VARCHAR(64) NOT NULL,
    doktor_uzmanlik VARCHAR(100) NOT NULL,
    doktor_tel VARCHAR(20) NOT NULL UNIQUE
);

CREATE TABLE hayvanlar (
    hayvan_id INT AUTO_INCREMENT PRIMARY KEY,
    sahip_id INT NOT NULL,
    hayvan_ad VARCHAR(64) NOT NULL,
    hayvan_tur VARCHAR(64) NOT NULL,
    hayvan_irk VARCHAR(64) NOT NULL,
    hayvan_dogum DATE NOT NULL,
    hayvan_cinsiyet ENUM('Erkek','Dişi') NOT NULL,
    FOREIGN KEY (sahip_id) REFERENCES sahipler(sahip_id)
        ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE muayeneler (
    muayene_id INT AUTO_INCREMENT PRIMARY KEY,
    hayvan_id INT NOT NULL,
    doktor_id INT NOT NULL,
    muayene_tarih DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    muayene_tani VARCHAR(250) NOT NULL,
    muayene_tedavi VARCHAR(250) NOT NULL,
    muayene_ucret DECIMAL(10,2) NOT NULL CHECK (muayene_ucret >= 0),
    FOREIGN KEY (hayvan_id) REFERENCES hayvanlar(hayvan_id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (doktor_id) REFERENCES doktorlar(doktor_id)
        ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE odemeler (
    odeme_id INT AUTO_INCREMENT PRIMARY KEY,
    sahip_id INT NOT NULL,
    odeme_tarih DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    odeme_tutar DECIMAL(10,2) NOT NULL CHECK (odeme_tutar > 0),
    odeme_tur ENUM('Nakit','Kredi Kartı','Banka Transferi') NOT NULL,
    odeme_aciklama VARCHAR(250),
    FOREIGN KEY (sahip_id) REFERENCES sahipler(sahip_id)
        ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE kullanicilar (
    kullanici_id INT AUTO_INCREMENT PRIMARY KEY,
    kullanici_ad VARCHAR(64) NOT NULL UNIQUE,
    kullanici_sifre VARCHAR(256) NOT NULL
);

-- ============================================
-- STORED PROCEDURES
-- ============================================

DELIMITER $$

CREATE PROCEDURE sp_SahipEkle(IN p_ad VARCHAR(64), IN p_soyad VARCHAR(64), IN p_tel VARCHAR(20), IN p_mail VARCHAR(100), IN p_adres VARCHAR(250))
BEGIN
    INSERT INTO sahipler(sahip_ad, sahip_soyad, sahip_tel, sahip_mail, sahip_adres)
    VALUES(p_ad, p_soyad, p_tel, p_mail, p_adres);
END$$

CREATE PROCEDURE sp_SahipGuncelle(IN p_id INT, IN p_ad VARCHAR(64), IN p_soyad VARCHAR(64), IN p_tel VARCHAR(20), IN p_mail VARCHAR(100), IN p_adres VARCHAR(250))
BEGIN
    UPDATE sahipler SET sahip_ad=p_ad, sahip_soyad=p_soyad, sahip_tel=p_tel, sahip_mail=p_mail, sahip_adres=p_adres
    WHERE sahip_id=p_id;
END$$

CREATE PROCEDURE sp_SahipSil(IN p_id INT)
BEGIN
    DELETE FROM sahipler WHERE sahip_id=p_id;
END$$

CREATE PROCEDURE sp_SahipListele()
BEGIN
    SELECT * FROM sahipler;
END$$

CREATE PROCEDURE sp_SahipBul(IN p_filtre VARCHAR(100))
BEGIN
    SELECT * FROM sahipler WHERE
    sahip_ad LIKE CONCAT('%',p_filtre,'%') OR
    sahip_soyad LIKE CONCAT('%',p_filtre,'%') OR
    sahip_tel LIKE CONCAT('%',p_filtre,'%');
END$$

CREATE PROCEDURE sp_DoktorEkle(IN p_ad VARCHAR(64), IN p_soyad VARCHAR(64), IN p_uzmanlik VARCHAR(100), IN p_tel VARCHAR(20))
BEGIN
    INSERT INTO doktorlar(doktor_ad, doktor_soyad, doktor_uzmanlik, doktor_tel)
    VALUES(p_ad, p_soyad, p_uzmanlik, p_tel);
END$$

CREATE PROCEDURE sp_DoktorGuncelle(IN p_id INT, IN p_ad VARCHAR(64), IN p_soyad VARCHAR(64), IN p_uzmanlik VARCHAR(100), IN p_tel VARCHAR(20))
BEGIN
    UPDATE doktorlar SET doktor_ad=p_ad, doktor_soyad=p_soyad, doktor_uzmanlik=p_uzmanlik, doktor_tel=p_tel
    WHERE doktor_id=p_id;
END$$

CREATE PROCEDURE sp_DoktorSil(IN p_id INT)
BEGIN
    DELETE FROM doktorlar WHERE doktor_id=p_id;
END$$

CREATE PROCEDURE sp_DoktorListele()
BEGIN
    SELECT * FROM doktorlar;
END$$

CREATE PROCEDURE sp_HayvanEkle(IN p_sahip_id INT, IN p_ad VARCHAR(64), IN p_tur VARCHAR(64), IN p_irk VARCHAR(64), IN p_dogum DATE, IN p_cinsiyet ENUM('Erkek','Dişi'))
BEGIN
    INSERT INTO hayvanlar(sahip_id, hayvan_ad, hayvan_tur, hayvan_irk, hayvan_dogum, hayvan_cinsiyet)
    VALUES(p_sahip_id, p_ad, p_tur, p_irk, p_dogum, p_cinsiyet);
END$$

CREATE PROCEDURE sp_HayvanGuncelle(IN p_id INT, IN p_ad VARCHAR(64), IN p_tur VARCHAR(64), IN p_irk VARCHAR(64), IN p_dogum DATE, IN p_cinsiyet ENUM('Erkek','Dişi'))
BEGIN
    UPDATE hayvanlar SET hayvan_ad=p_ad, hayvan_tur=p_tur, hayvan_irk=p_irk, hayvan_dogum=p_dogum, hayvan_cinsiyet=p_cinsiyet
    WHERE hayvan_id=p_id;
END$$

CREATE PROCEDURE sp_HayvanSil(IN p_id INT)
BEGIN
    DELETE FROM hayvanlar WHERE hayvan_id=p_id;
END$$

CREATE PROCEDURE sp_HayvanListele()
BEGIN
    SELECT h.*, CONCAT(s.sahip_ad,' ',s.sahip_soyad) as sahip_adi
    FROM hayvanlar h
    JOIN sahipler s ON h.sahip_id=s.sahip_id;
END$$

CREATE PROCEDURE sp_MuayeneEkle(IN p_hayvan_id INT, IN p_doktor_id INT, IN p_tani VARCHAR(250), IN p_tedavi VARCHAR(250), IN p_ucret DECIMAL(10,2))
BEGIN
    INSERT INTO muayeneler(hayvan_id, doktor_id, muayene_tani, muayene_tedavi, muayene_ucret)
    VALUES(p_hayvan_id, p_doktor_id, p_tani, p_tedavi, p_ucret);
END$$

CREATE PROCEDURE sp_MuayeneGuncelle(IN p_id INT, IN p_tani VARCHAR(250), IN p_tedavi VARCHAR(250), IN p_ucret DECIMAL(10,2))
BEGIN
    UPDATE muayeneler SET muayene_tani=p_tani, muayene_tedavi=p_tedavi, muayene_ucret=p_ucret
    WHERE muayene_id=p_id;
END$$

CREATE PROCEDURE sp_MuayeneSil(IN p_id INT)
BEGIN
    DELETE FROM muayeneler WHERE muayene_id=p_id;
END$$

CREATE PROCEDURE sp_MuayeneListele()
BEGIN
    SELECT m.*, h.hayvan_ad, CONCAT(d.doktor_ad,' ',d.doktor_soyad) as doktor_adi
    FROM muayeneler m
    JOIN hayvanlar h ON m.hayvan_id=h.hayvan_id
    JOIN doktorlar d ON m.doktor_id=d.doktor_id;
END$$

CREATE PROCEDURE sp_OdemeEkle(IN p_sahip_id INT, IN p_tutar DECIMAL(10,2), IN p_tur ENUM('Nakit','Kredi Kartı','Banka Transferi'), IN p_aciklama VARCHAR(250))
BEGIN
    INSERT INTO odemeler(sahip_id, odeme_tutar, odeme_tur, odeme_aciklama)
    VALUES(p_sahip_id, p_tutar, p_tur, p_aciklama);
END$$

CREATE PROCEDURE sp_OdemeGuncelle(IN p_id INT, IN p_tutar DECIMAL(10,2), IN p_tur ENUM('Nakit','Kredi Kartı','Banka Transferi'), IN p_aciklama VARCHAR(250))
BEGIN
    UPDATE odemeler SET odeme_tutar=p_tutar, odeme_tur=p_tur, odeme_aciklama=p_aciklama
    WHERE odeme_id=p_id;
END$$

CREATE PROCEDURE sp_OdemeSil(IN p_id INT)
BEGIN
    DELETE FROM odemeler WHERE odeme_id=p_id;
END$$

CREATE PROCEDURE sp_OdemeListele()
BEGIN
    SELECT o.*, CONCAT(s.sahip_ad,' ',s.sahip_soyad) as sahip_adi
    FROM odemeler o
    JOIN sahipler s ON o.sahip_id=s.sahip_id;
END$$

CREATE PROCEDURE sp_KullaniciGetir(IN p_ad VARCHAR(64))
BEGIN
    SELECT * FROM kullanicilar WHERE kullanici_ad = p_ad;
END$$

-- ============================================
-- FUNCTIONS
-- ============================================

CREATE FUNCTION fn_SahipToplamBorc(p_sahip_id INT)
RETURNS DECIMAL(10,2)
DETERMINISTIC
BEGIN
    DECLARE toplam DECIMAL(10,2);
    SELECT COALESCE(SUM(m.muayene_ucret), 0) INTO toplam
    FROM muayeneler m
    JOIN hayvanlar h ON m.hayvan_id = h.hayvan_id
    WHERE h.sahip_id = p_sahip_id;
    RETURN toplam;
END$$

CREATE FUNCTION fn_HayvanMuayeneSayisi(p_hayvan_id INT)
RETURNS INT
DETERMINISTIC
BEGIN
    DECLARE sayi INT;
    SELECT COUNT(*) INTO sayi
    FROM muayeneler
    WHERE hayvan_id = p_hayvan_id;
    RETURN sayi;
END$$

-- ============================================
-- TRIGGERS
-- ============================================

-- Trigger 1: Muayene ücreti negatif olamaz (INSERT)
CREATE TRIGGER tg_muayene_ucret_kontrol
BEFORE INSERT ON muayeneler
FOR EACH ROW
BEGIN
    IF NEW.muayene_ucret < 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Hata: Muayene ücreti 0dan küçük olamaz!';
    END IF;
END$$

-- Trigger 2: Hayvan doğum tarihi bugünden ileri olamaz
CREATE TRIGGER tg_hayvan_dogum_kontrol
BEFORE INSERT ON hayvanlar
FOR EACH ROW
BEGIN
    IF NEW.hayvan_dogum > CURDATE() THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Hata: Hayvanın doğum tarihi bugünden ileri bir tarih olamaz!';
    END IF;
END$$

DELIMITER ;

-- ============================================
-- VARSAYILAN KULLANICI (admin / password)
-- ============================================

INSERT INTO kullanicilar (kullanici_ad, kullanici_sifre)
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');
