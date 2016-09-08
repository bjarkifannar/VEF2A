<div class="site-wrap">
	<header>
		<div class="header-wrap">
			<nav>
				<ul class="header-menu">
					<?php
						if ($pageName == 'Forsíða') {
							echo '<li><a href="index.php" class="current-page">Forsíða</a></li>';
						} else {
							echo '<li><a href="index.php">Forsíða</a></li>';
						}

						if ($pageName == 'Síða 2') {
							echo '<li><a href="page2.php" class="current-page">Síða 2</a></li>';
						} else {
							echo '<li><a href="page2.php">Síða 2</a></li>';
						}
					?>
				</ul>
			</nav>
		</div>
	</header>
	<div class="content-wrap">
		<div class="main">