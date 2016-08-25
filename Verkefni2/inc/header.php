<?php
	$imgArray = array("Eldgos" => "img-1.jpg",
						"Eyjafjallajökull" => "img-2.jpeg",
						"Fjall" => "img-3.jpg",
						"Torfbæir" => "img-4.jpg");

	$imgTitle = array_rand($imgArray, 1);
	$imgName = $imgArray[$imgTitle];
?>
<div class="site-wrap">
	<header>
		<div class="header-wrap">
			<nav>
				<ul class="header-menu">
					<li><a href="index.php">Forsíða</a></li>
					<li><a href="page2.php">Síða 2</a></li>
				</ul>
			</nav>
		</div>
	</header>
	<div class="content-wrap">
		<div class="main">
			<h2 class="page-title"><?php echo $pageName; ?></h2>
			<div class="section-split"></div>
			<img class="top-img" src="<?php echo 'img/'.$imgName; ?>">
			<h3 class="top-img-title"><?php echo $imgTitle; ?></h3>