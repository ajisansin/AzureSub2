<?php
if (isset($_POST['submit'])) {
	if (isset($_POST['url'])) {
		$url = $_POST['url'];
	} else {
		header("Location: index.php");
	}
} else {
	header("Location: index.php");
}
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
        		<h1>Analisa Gambar</h1>
				<p class="lead">Berikut hasil analisa</p>
				<span class="border-top my-3"></span>
			</div>
        <script type="text/javascript">
            $(document).ready(function () {
            // **********************************************
            // *** Update or verify the following values. ***
            // **********************************************
            // Replace <Subscription Key> with your valid subscription key.
            var subscriptionKey = "f6ea14ce72e34ae0bee88f4fc6a62297";
            // You must use the same Azure region in your REST API method as you used to
            // get your subscription keys. For example, if you got your subscription keys
            // from the West US region, replace "westcentralus" in the URL
            // below with "westus".
            //
            // Free trial subscription keys are generated in the "westus" region.
            // If you use a free trial subscription key, you shouldn't need to change
            // this region.
            var uriBase =
            "https://southeastasia.api.cognitive.microsoft.com/vision/v2.0/analyze";
            // Request parameters.
            var params = {
                "visualFeatures": "Categories,Description,Color",
                "details": "",
                "language": "en",
            };
            // Display the image.
            var sourceImageUrl = "<?php echo $url ?>";
            document.querySelector("#sourceImage").src = sourceImageUrl;
            // Make the REST API call.
            $.ajax({
                url: uriBase + "?" + $.param(params),
                // Request headers.
                beforeSend: function(xhrObj){
                    xhrObj.setRequestHeader("Content-Type","application/json");
                    xhrObj.setRequestHeader("Ocp-Apim-Subscription-Key", subscriptionKey);
                },
                type: "POST",
                // Request body.
                data: '{"url": ' + '"' + sourceImageUrl + '"}',
            })
            .done(function(data) {
                // Show formatted JSON on webpage.
                $("#responseTextArea").val(JSON.stringify(data, null, 2));
                // console.log(data);
                // var json = $.parseJSON(data);
                $("#description").text(data.description.captions[0].text);
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                // Display error message.
                var errorString = (errorThrown === "") ? "Error. " :
                errorThrown + " (" + jqXHR.status + "): ";
                errorString += (jqXHR.responseText === "") ? "" :
                jQuery.parseJSON(jqXHR.responseText).message;
                alert(errorString);
            });
        });
    </script>
<br>
<div id="wrapper" style="width:1020px; display:table;">
	<div id="jsonOutput" style="width:600px; display:table-cell;">
		<b>Response:</b>
		<br><br>
		<textarea id="responseTextArea" class="UIInput"
		style="width:580px; height:400px;" readonly=""></textarea>
	</div>
	<div id="imageDiv" style="width:420px; display:table-cell;">
		<b>Source Image:</b>
		<br><br>
		<img id="sourceImage" width="400" />
		<br>
		<h3 id="description">Loading description. . .</h3>
	</div>
</div>
</body>
</html>
