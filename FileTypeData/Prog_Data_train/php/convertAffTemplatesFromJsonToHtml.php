<?php
$srcDir = 'D:\\code\\hostelbookers\\data\\affiliateTrackingTemplates\\';
$destDir = 'D:\\code\\hostelbookers\\src\\wri\hostelbookers\\templates\\affiliateTrackingTemplates\\';
$srcDirIterator = new DirectoryIterator ($srcDir);

$templateCurrencyMapping = [];

foreach($srcDirIterator as $file){
    $fileName = $file->getFilename();
    $fileMatch = preg_match('/affiliateTemplate(\d+)\.json/',$fileName, $matches);
    if (!$fileMatch){
        continue;
    }
    $fileId = $matches[1];
    $filePath = $file->getPathname();
    $json = file_get_contents($filePath);
    $templateDetails = json_decode($json);
    $updatedTemplateDetails = [
        'currency' => $templateDetails->currency,
        'templateId' => $fileId,
        'scriptTemplate' => $templateDetails->scriptTemplate
    ];
    $destFileName = "$fileId.html";
    $destFilePath = $destDir . $destFileName;
    file_put_contents($destFilePath, $templateDetails->scriptTemplate);

    $updatedTemplateDetailsAsJson = json_encode($updatedTemplateDetails);
    file_put_contents($filePath, $updatedTemplateDetailsAsJson);

    $templateCurrencyMapping[$fileId] = $templateDetails->currency;
}

echo json_encode($templateCurrencyMapping);