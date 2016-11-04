<div class="site-wrap">
	<header>
		<div class="header-wrap">
			<nav>
				<ul class="header-menu">
					<li><a href="index.php">Admin Panel</a></li>
					<?php
						if ($logged === 'in') {
							echo '<li><a href="doc.php">Documentation</a></li>';
							echo '<li><a href="logout.php">Logout</a></li>';
						}
					?>
				</ul>
			</nav>
		</div>
	</header>
	<div class="admin-content-wrap">
		<div class="admin-main">