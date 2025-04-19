param(
    [Parameter(Position=0)]
    [string]$Target
)

# Get current working directory
$CWD = Get-Location

# Load environment variables from .env.local
Get-Content ".env.local" | ForEach-Object {
    if ($_ -match '^(.+)=(.+)$') {
        [Environment]::SetEnvironmentVariable($matches[1], $matches[2])
    }
}

# Run docker compose with dev configuration
docker compose -f compose.yaml exec php sh -c "
cd /var/www/html;
composer install;
"

exit 0