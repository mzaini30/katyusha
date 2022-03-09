<!DOCTYPE html>
<html lang="en">
<head>
	<?php include $_SERVER['DOCUMENT_ROOT'] . '/katyusha.php' ?>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Document</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" />
	<style>
		h1 {
			font-size: 1rem;
		}
	</style>
</head>
<body>
	<div class="container py-3">
		<h1>Ura</h1>
		<img src="https://www.animenewsnetwork.com/images/encyc/A14334-989842639.1337789480.jpg" alt="" />
	</div>
	<script data-nama='satu' data-script='dua' data-src='tiga' src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script>
		try {
			$('h1').css({
				'font-style': 'italic'
			})
		} catch(x){
			console.log(x)
		}
	</script>
</body>
</html>