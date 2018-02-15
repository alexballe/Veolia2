<div id="table" class="table-responsive">
	<table align="center">
		<tbody>
			<tr>
				<th>Identification</th>
				<th>Poids</th>
				<th>% de remplissage</th>
				<th>Frequence d'utilisation</th>
				<th>Longitude</th>
				<th>Latitude</th>
			</tr>
		
		<?php 
			$monPDO = new PDO('mysql:host=127.0.0.1;dbname=Veolia;charset=utf8','root','');
			$mabdd = $monPDO->query('SELECT * FROM Etat');
			while($mesdonnee = $mabdd->fetch())
			{
				echo '<tr><td>'.$mesdonnee['Identification'].'</td>';
				echo '<td>'.$mesdonnee['Poids'].'</td>';
				echo '<td>'.$mesdonnee['% de remplissage'].'</td>';
				echo '<td>'.$mesdonnee["Frequence d'utilisation"].'</td>';
				echo '<td>'.$mesdonnee['Longitude'].'</td>';
				echo '<td>'.$mesdonnee['Latitude'].'</td></tr>';
			}
		?>
		</tbody>
	</table>
</div>