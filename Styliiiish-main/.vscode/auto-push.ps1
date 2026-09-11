
git status

$changes = git status --porcelain

if ($changes) {

    git add .

    $time = Get-Date -Format "yyyy-MM-dd HH:mm"

    git commit -m "Auto update $time"

    git push origin main

    Write-Host "✅ Update pushed successfully"

} else {

    Write-Host "⚠️ No changes to push"
}

pause
