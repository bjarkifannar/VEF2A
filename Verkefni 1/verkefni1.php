<!DOCTYPE html>
<html>
	<head>
		<title>VEF2A - Verkefni 1</title>
		<meta charset="utf-8">
		<style type="text/css">
			table {
				border-collapse: collapse;
			}

			table td {
				padding: 0.25em;
				border: 1px solid black;
			}
		</style>
	</head>
	<body>
		<h2 align="center">VEF2A - Verkefni 1 - PHP Dæmi</h2>
		<h3 align="center">Svörin eru í Verkefni1.txt</h3>
		<p><b>2. Hver er munurinn á einföldum gæsalöppum og tvöföldum, sýndu mér kóðadæmi sem sýnir mismunandi niðurstöðu</b></p>
		<?php
			$breyta = 'Þetta er breyta!';
			echo 'Einfaldar gæsalappir: $breyta';
			echo '<br>';
			echo "Tvöfaldar gæsalappir: $breyta";
		?>
		<p><b>3. Hvað er $GLOBALS, hvernig virkar það, komdu með dæmi.</b></p>
		<?php
			$breyta = 'Breyta';
			echo $GLOBALS['breyta'];
		?>
		<p><b>4. Hvenær myndir þú nota === virkjann fremur en ==, komdu með dæmi</b></p>
		<?php
			$breyta1 = '1';
			$breyta2 = 1;

			if ($breyta1 == $breyta2) {
				echo 'Breyta 1 == Breyta 2';
			} else {
				echo 'Breyta 1 !== breyta 2';
			}

			echo '<br>';

			if ($breyta1 === $breyta2) {
				echo 'Breyta 1 === Breyta 2';
			} else {
				echo 'Breyta 1 !=== Breyta 2';
			}
		?>
		<p><b>5. printf() er sniðugt af tveimur ástæðum hverjar eru þær? komdu einnig með kóðadæmi.</b></p>
		<?php
			$hello = 'Hello';

			printf("%s World!", $hello);
		?>
		<p><b>8. Búðu til nefnt fylki (Associative array) með eftirfarandi borgum og löndum; Japan -Tokyo, Mexico - Mexico City, USA - New York City, India - Mumbai, Korea - Seoul, China - Shanghai. Notið foreach til að birta Lönd og borgir</b></p>
		<?php
			$borgir = array('Japan' => 'Tokyo', 'Mexico' => 'Mexico City', 'USA' => 'New York City', 'India' => 'Mumbai', 'Korea' => 'Seoul', 'China' => 'Shanghai');

			foreach ($borgir as $land => $borg) {
				echo $land.' - '.$borg.'<br>';
			}
		?>
		<p><b>9. Notaðu echo og list() til að birta á skjá eftrifarandi gildi úr $colors.</b></p>
		<?php
			$colors = array("red", "blue", "green");
			list($g, $b, $r) = $colors;

			echo $r.', '.$b.', '.$g;
		?>
		<p><b>10. Gefið er fylkið $states = array("Texas", "Ohio"); Bættu við aftast New York og bættu við fremst California. (notaðu innbyggt php fall til að ná þessu fram)</b></p>
		<?php
			$states = array('Texas', 'Ohio');
			array_push($states, 'New York');
			array_unshift($states, 'California');
			print_r($states);
		?>
		<p><b>11. Notaðu shuffle() fallið á fylkið $states og birtu útkomuna</b></p>
		<?php
			shuffle($states);
			print_r($states);
		?>
		<p><b>12. Birtu í töfluformi (html) eftirfarandi gögn úr fylkinu $products. </b></p>
		<?php
			$products = array(array('08:10', '10:30', '13:15'), array('GSÖ2B2U', 'VSH2B2U', 'FOR2B2U'), array('GUS', 'GJG', 'GRL'));

			echo '<table>';

			for ($i = 0; $i < count($products); $i++) {
				echo '<tr>';

				for ($j = 0; $j < count($products[$i]); $j++) {
					echo '<td>'.$products[$j][$i].'</td>';
				}

				echo '</tr>';
			}

			echo '</table>';
		?>
		<p><b>13. Gefið er eftirfarandi $customers fylki sem geymir önnur fylki (2d array):</b></p>
		<?php
			$customers[] = array("Jason Gilmore", "jason@example.com", "614-999-9999");
			$customers[] = array("Jesse James", "jesse@example.net", "818-999-9999");
			$customers[] = array("Donald Duck", "donald@example.org", "212-999-9999");

			foreach ($customers as $customer) {
				vprintf("<p>Name: %s<br />E-mail: %s<br />Phone: %s</p>", $customer);
			}
		?>
		<p><b>14. Hver er útkoman með að nota a) sort() fallið og hinsvegar natcasesort() á $pic? $pic = array("pic2.jpg", "PIC10.jpg", "pic20.jpg", "pic1.jpg");</b></p>
		<?php
			$pic = array("pic2.jpg", "PIC10.jpg", "pic20.jpg", "pic1.jpg");
			
			sort($pic);
			echo 'sort(): ';
			print_r($pic);

			natcasesort($pic);
			echo '<br>natcasesort(): ';
			print_r($pic);
		?>
	</body>
</html>