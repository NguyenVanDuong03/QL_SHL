param(
    [string]$Server = "deploy@159.65.15.35",
    [string]$SshKey = "C:\Users\Admin\.ssh\id_ed25519_digitalocean",
    [string]$RemoteDir = "/var/www/ql_shl",
    [string]$Branch = "develop"
)

$ErrorActionPreference = "Stop"

$remoteCmd = @"
set -e
cd $RemoteDir
git fetch --all
git checkout $Branch
git pull origin $Branch
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
"@

ssh -i $SshKey $Server $remoteCmd
Write-Host "Deploy ql_shl done."
