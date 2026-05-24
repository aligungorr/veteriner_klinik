# 🐾 VetKlinik Pro - Veteriner Klinik Yönetim Sistemi

BTS304 Veritabanı Yönetim Sistemleri II - Final Ödevi

---

## 📋 Proje Hakkında

Bu uygulama bir veteriner kliniği için geliştirilmiş web tabanlı yönetim sistemidir.  
PHP ve MySQL kullanılarak N-Katmanlı mimari ile geliştirilmiştir.

### Özellikler
- Hayvan sahiplerini yönetme
- Veteriner doktorlarını yönetme
- Hayvan kayıtlarını yönetme
- Muayene kayıtlarını yönetme
- Ödeme takibi

---

## 🛠️ Kurulum

### Gereksinimler
- XAMPP (Apache + PHP)
- MySQL 8.0+
- Web tarayıcı

### Adımlar

**1. Projeyi İndirin**
```
Code → Download ZIP
```
ZIP dosyasını açın ve klasörü şu konuma kopyalayın:
```
C:\xampp\htdocs\vetklinik
```

**2. Veritabanını Oluşturun**

MySQL Workbench veya phpMyAdmin'i açın ve  
`veteriner_klinik.sql` dosyasını import edin.

MySQL Workbench ile:
```
File → Open SQL Script → veteriner_klinik.sql → Execute (⚡)
```

**3. Veritabanı Bağlantısını Ayarlayın**

`dal/db.php` dosyasını açın ve kendi MySQL bilgilerinizi girin:
```php
$host = "localhost";
$user = "root";
$password = "MYSQL_SIFRENIZ";
$database = "veteriner_klinik";
```

**4. XAMPP'ı Başlatın**

XAMPP Control Panel'den **Apache**'yi başlatın.

**5. Tarayıcıdan Açın**
```
http://localhost/vetklinik/giris.php
```

---

## 🔐 Giriş Bilgileri

| Kullanıcı Adı | Şifre    |
|---------------|----------|
| admin         | password |

---

## 🏗️ Proje Yapısı

```
vetklinik/
├── bl/
│   └── services.php        # Business Layer
├── dal/
│   └── db.php              # Data Access Layer
├── giris.php               # Giriş sayfası
├── index.php               # Dashboard
├── sahipler.php            # Sahip yönetimi
├── doktorlar.php           # Doktor yönetimi
├── hayvanlar.php           # Hayvan yönetimi
├── muayeneler.php          # Muayene yönetimi
├── odemeler.php            # Ödeme yönetimi
├── layout.php              # Ortak layout
├── layout_footer.php       # Footer
├── cikis.php               # Çıkış
└── veteriner_klinik.sql    # Veritabanı
```

---

## 🗄️ Veritabanı Yapısı

| Tablo        | Açıklama              |
|--------------|-----------------------|
| sahipler     | Hayvan sahipleri      |
| doktorlar    | Veteriner doktorlar   |
| hayvanlar    | Kayıtlı hayvanlar     |
| muayeneler   | Muayene kayıtları     |
| odemeler     | Ödeme kayıtları       |
| kullanicilar | Sistem kullanıcıları  |

---

## 📚 Teknolojiler

- **Backend:** PHP 8
- **Veritabanı:** MySQL 8
- **Frontend:** HTML, CSS, Bootstrap 5
- **Mimari:** N-Katmanlı (Presentation - Business - Data Access)
- **DB Erişimi:** Stored Procedure, Function, Trigger
