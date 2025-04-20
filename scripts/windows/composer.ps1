param(
    [Parameter(ValueFromRemainingArguments=$true)]
    [string[]]$Arguments
)

# Get current working directory
$CWD = Get-Location

# Load environment variables from .env.local
Get-Content ".env.local" | ForEach-Object {
    if ($_ -match '^(.+)=(.+)$') {
        [Environment]::SetEnvironmentVariable($matches[1], $matches[2])
    }
}

# Join all arguments with spaces to create the command
$consoleArgs = $Arguments -join ' '
$CMD = "cd /var/www/html && composer $consoleArgs"

Write-Host "Executing command: $CMD"

# Run docker compose with dev configuration
docker compose -f compose.yaml exec php sh -c "$CMD"
exit $LASTEXITCODE
