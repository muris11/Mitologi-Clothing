<#
Run all local services for the repository in separate terminals.

Usage:
  PowerShell (recommended, from repo root):
    powershell -ExecutionPolicy Bypass -File .\dev-all.ps1

This script will try to open Windows Terminal (`wt`) tabs. If `wt` is not available
it will fall back to opening separate PowerShell windows.
#>

$RepoRoot = Split-Path -Parent $MyInvocation.MyCommand.Path

$services = @(
    @{ Path = Join-Path $RepoRoot 'backend'; Command = 'composer dev' },
    @{ Path = Join-Path $RepoRoot 'nextjs-commerce'; Command = 'pnpm dev -- -p 3011' },
    @{ Path = Join-Path $RepoRoot 'recommendation-service'; Command = 'python .\app.py' },
    @{ Path = Join-Path $RepoRoot 'backend'; Command = 'php artisan serve --host=0.0.0.0 --port 8011' }
)

Write-Host "Opening each service in its own PowerShell window."
foreach ($s in $services) {
    $cwd = $s.Path
    $cmd = $s.Command
    $inner = "Set-Location -LiteralPath '$cwd'; $cmd"
    $arg = @('-NoExit', '-Command', $inner)
    Start-Process -FilePath powershell -ArgumentList $arg
}
