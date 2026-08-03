# Deploy Server diskominfo dengan AA Panel

## Create Site

1. Pergi Ke Menu website -> Klik Add Site
2. buat domain/subdomain

## Create database

1. Cari menu database
2. klik Add DB
3. Simpan dbname, username, dan password yang di berikan

## Cloning

1. buka terminal server
2. pergi ke directory website yang telah di buatkan

```bash
cd /www/wwwroot/namadomain
```

3. clone repository dari github
   note\*: karena menggunakan AA panel, harus menggunakan sudo untuk cloning repository

```bash
sudo git clone nama-repository.git
```

## Staging and build

1. masuk ke directory dari github dan copy .env.example ke .env (ubah sesuai kebutuhan)
2. jalankan perintah build no dev

download terlebih dahulu composer phar nya

```bash
sudo /www/server/php/84/bin/php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
sudo /www/server/php/84/bin/php composer-setup.php
sudo /www/server/php/84/bin/php -r "unlink('composer-setup.php');"
```

```bash
sudo /www/server/php/84/bin/php composer.phar install --optimize-autoloader --no-dev
```

3. eksekusi artisan

```bash
sudo /www/server/php/84/bin/php artisan migrate --force
sudo /www/server/php/84/bin/php artisan db:seed --class=AdminSeeder --force
sudo /www/server/php/84/bin/php artisan key:generate
sudo /www/server/php/84/bin/php artisan storage:link
sudo /www/server/php/84/bin/php artisan livewire:publish --assets (optional)
sudo /www/server/php/84/bin/php artisan optimize:clear
sudo /www/server/php/84/bin/php artisan optimize
sudo /www/server/php/84/bin/php artisan filament:optimize
```

4. Kembalikan Hak akses Ke server kembali

```bash
sudo chattr -i ./public/.user.ini 2>/dev/null
sudo chown -R www:www .
sudo chmod -R 775 storage bootstrap/cache
sudo chattr +i ./public/.user.ini 2>/dev/null
```

## setting workdir dan url rewrite

1. setting workdir di website menjadi ke folder public dari project
2. buka di tab url rewite masukan code berikut

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Handle X-XSRF-Token Header
    RewriteCond %{HTTP:x-xsrf-token} .
    RewriteRule .* - [E=HTTP_X_XSRF_TOKEN:%{HTTP:X-XSRF-Token}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

## link ke cloudflare

buat record dan link kan ke cloudflare
