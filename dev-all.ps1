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
    @{ Path = Join-Path $RepoRoot 'nextjs-commerce'; Command = 'npm run dev' },
    @{ Path = Join-Path $RepoRoot 'recommendation-service'; Command = 'python .\app.py' },
    @{ Path = Join-Path $RepoRoot 'backend'; Command = 'php artisan serve --host=0.0.0.0 --port 8011' }
)

if (Get-Command wt -ErrorAction SilentlyContinue) {
    # Build a single wt command that opens a tab per service
    $wtParts = @()
    foreach ($s in $services) {
        $cwd = $s.Path -replace '"','\\"'
        $cmd = $s.Command -replace '"','\\"'
        $wtParts += "new-tab -d `"$cwd`" powershell -NoExit -Command cd `"$cwd`"; $cmd"
    }
    $wtCmd = "wt " + ($wtParts -join ' ; ')
    Write-Host "Launching Windows Terminal with multiple tabs..."
    Invoke-Expression $wtCmd
} else {
    Write-Host "Windows Terminal (wt) not found — opening separate PowerShell windows."
    foreach ($s in $services) {
        $cwd = $s.Path
        $cmd = $s.Command
        Start-Process -FilePath powershell -ArgumentList "-NoExit","-Command","Set-Location -LiteralPath '$cwd'; $cmd"
    }
}
