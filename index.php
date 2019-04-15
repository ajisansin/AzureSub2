<?php
require_once 'vendor/autoload.php';
require_once "./random_string.php";
use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;
use MicrosoftAzure\Storage\Blob\Models\CreateContainerOptions;
use MicrosoftAzure\Storage\Blob\Models\PublicAccessType;
// Koneksikan blob storage
$connectionString = "DefaultEndpointsProtocol=https;AccountName=sinaubarengazurestorage;AccountKey=csLFrHU3SCrSlDLKPcTYO2BCHyN8HCfcB86Qovc6IG4YOnolrDIObNRaW/ojEQPx88Wh/4C98H8XYNAhhqoZAQ==;";
$containerName = "blobsinaubareng";
// Membuat blob client.
    $blobClient = BlobRestProxy::createBlobService($connectionString);
	    
if (isset($_POST['submit'])) {
	$fileToUpload = strtolower($_FILES["fileToUpload"]["name"]);
	$content = fopen($_FILES["fileToUpload"]["tmp_name"], "r");
	// echo fread($content, filesize($fileToUpload));
	$blobClient->createBlockBlob($containerName, $fileToUpload, $content);
	header("Location: index.php");
}
$listBlobsOptions = new ListBlobsOptions();
$listBlobsOptions->setPrefix("");
$result = $blobClient->listBlobs($containerName, $listBlobsOptions);
?>

<!DOCTYPE html>
<html>
 <head>
 <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	 
	    <link rel="canonical" href="https://getbootstrap.com/docs/4.0/examples/starter-template/">
	 <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.3.1.min.js"></script>
	    <!-- Bootstrap core CSS -->
	    <link href="https://getbootstrap.com/docs/4.0/dist/css/bootstrap.min.css" rel="stylesheet">
	</head>
    <body>
		<main role="main" class="container">
    		<div class="starter-template"> <br><br><br>
        		<h1>Analisis Gambar</h1>
				<p class="lead">Pilih Gambar.<br> Kemudian Klik <b>Upload</b>, untuk menganalisa gambar pilih <b>Analyze</b> pada tabel di bawah.</p>
				<span class="border-top my-3"></span>
			</div>
		<div class="mt-4 mb-2">
			<form class="d-flex justify-content-lefr" action="index.php" method="post" enctype="multipart/form-data">
				<input type="file" name="fileToUpload" accept=".jpeg,.jpg,.png" required="">
				<input type="submit" name="submit" value="Upload">
			</form>
		</div>
		<br>
		<br>
		<h4>Total Files : <?php echo sizeof($result->getBlobs())?></h4>
		<table class='table table-hover'>
			<thead>
				<tr>
					<th>File Name</th>
					<th>File URL</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<?php
				do {
					foreach ($result->getBlobs() as $blob)
					{
						?>
						<tr>
							<td><?php echo $blob->getName() ?></td>
							<td><?php echo $blob->getUrl() ?></td>
							<td>
								<form action="compvision.php" method="post">
									<input type="hidden" name="url" value="<?php echo $blob->getUrl()?>">
									<input type="submit" name="submit" value="Analyze" class="btn btn-primary">
								</form>
							</td>
						</tr>
						<?php
					}
					$listBlobsOptions->setContinuationToken($result->getContinuationToken());
				} while($result->getContinuationToken());
				?>
			</tbody>
		</table>

	</div>

<!-- Placed at the end of the document so the pages load faster -->
    <script src="https://getbootstrap.com/docs/4.0/assets/js/vendor/popper.min.js"></script>
    <script src="https://getbootstrap.com/docs/4.0/dist/js/bootstrap.min.js"></script>
  </body>
</html>
