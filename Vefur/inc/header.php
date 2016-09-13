<div class="site-wrap">
	<header>
		<div class="header-wrap">
			<nav>
				<ul class="header-menu">
					<?php
						if ($pageName == 'Núna') {
							echo '<li><a href="index.php" class="current-page">Núna</a></li>';
						} else {
							echo '<li><a href="index.php">Núna</a></li>';
						}

						if ($pageName == 'Allt') {
							echo '<li><a href="page2.php" class="current-page">Allt</a></li>';
						} else {
							echo '<li><a href="page2.php">Allt</a></li>';
						}
					?>
				</ul>
			</nav>
		</div>
	</header>
	<div class="content-wrap">
		<div class="main">