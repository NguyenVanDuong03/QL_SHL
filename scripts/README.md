# Deploy QL_SHL

Tài liệu deploy nhanh cho dự án **QL_SHL**.

## 1) Deploy (mặc định branch `develop`)

```powershell
./scripts/deploy-qlshl.ps1 -Branch develop
```

Script sẽ thực hiện trên server:

- `git fetch --all`
- `git checkout <branch>` và `git pull origin <branch>`
- `composer install --no-dev --optimize-autoloader`
- `php artisan migrate --force`
- `php artisan config:cache`, `route:cache`, `view:cache`

## 2) Tùy chỉnh SSH key / server / branch

```powershell
./scripts/deploy-qlshl.ps1 `
  -Server "deploy@159.65.15.35" `
  -SshKey "C:\Users\Admin\.ssh\id_ed25519_digitalocean" `
  -Branch develop
```

> Chạy lệnh từ thư mục gốc dự án `QL_SHL` bằng PowerShell.
