<?php
	$monPDO = new PDO('mysql:host=127.0.0.1;dbname=Veolia;charset=utf8','root','');
	$mabdd = $monPDO->query('SELECT * FROM Etat');
	$i=0;
	while($mesdonnee = $mabdd->fetch())
	{	
		if ($mesdonnee['% de remplissage'] > 75 || $mesdonnee['Poids'] > 5)
		{
			$ID[$i] = $mesdonnee['Identification'];
			$long[$i] = $mesdonnee['Longitude'];
			$lat[$i] = $mesdonnee['Latitude'];
			$Poids[$i] = $mesdonnee['Poids'];
			$Remplissage[$i] = $mesdonnee['% de remplissage'];
			$Ouverture[$i] = $mesdonnee["Frequence d'utilisation"];
			$i++;
		}
	}
?>
<div id="code_map">

	<?php	
		echo "<div class =\"poubaffiche\"><p>Point de départ A : Centre de traitement</p>Nombre total de poubelle pleine : ".$i."</div>"; 
	?>
	<script>
//-----------------------------------------------------------------------------------------------------------		

		//Initialisation des variables 
		var lat = new Array();
		var long = new Array();
		var tableauMarqueurs = new Array();
		var contentString = new Array();
		var directionsDisplay = new google.maps.DirectionsRenderer();
		var directionsService = new google.maps.DirectionsService();
		var i = 0, marqueur, maCarte, infowindow, zoneMarqueurs, request, content, j = 0, point = 1, infopoint = 1;
		
		<?php 	

			//Insertion des données recuperer en base php dans des tableau javascript
	 		for($j=0; $j<$i;$j++)
	 		{ 
		 		if(isset($long[$j]) && isset($lat[$j]))
				{

		?>
					lat[point] = <?php echo $lat[$j]; ?>;
					long[point] = <?php echo $long[$j]; ?>;
					point++;
		<?php 
				}  
			}
		?>
	
//-----------------------------------------------------------------------------------------------------------

		//Creation d'un point de depart, le centre de traitement 
		tableauMarqueurs[0] = new google.maps.LatLng(49.8775453, 2.2976631);
		contentString[0] = '<div>Centre de traitement</div>';

		//Creation de tableaux d'infoview et point LatLng
		for(infopoint = 1; infopoint <point; infopoint++)
		{

			tableauMarqueurs[infopoint]  = new google.maps.LatLng(lat[infopoint], long[infopoint]);
	
		}
	
//------------------------------------------------------------------------------------------------------------

		//Creation d'une carte Google Map
		maCarte = new google.maps.Map( document.getElementById("map"), {
			zoom: 13,
			center: tableauMarqueurs[0],
			mapTypeId: google.maps.MapTypeId.ROADMAP
		});

		//Creation d'une bulle d'info de marker
		infowindow = new google.maps.InfoWindow();  

		if (tableauMarqueurs.length > 1)
		{
			directionsDisplay.setMap(maCarte);

			//Creation d'une zone de marker
			zoneMarqueurs = new google.maps.LatLngBounds();

			request = {
	            travelMode: google.maps.TravelMode.DRIVING, // route / voiture
	            optimizeWaypoints : true
	        };
	    }


//-----------------------------------------------------------------------------------------------------------

		//Creation des markers 
		for( i = 0; i < tableauMarqueurs.length; i++ ) {
	    	
	    	//Creation du marker 
			marqueur = new google.maps.Marker({
				position: tableauMarqueurs[i]
			});
			
			//Si il y a plus d'un marqueur 
			if (tableauMarqueurs.length > 1)
			{
				//Creation d'une zone 
				zoneMarqueurs.extend( marqueur.getPosition() );
			}

//-----------------------------------------------------------------------------------------------------------

	 		//Si il n'y qu'un point, on affiche qu'un marqueur sans itinéraire
	 		if (tableauMarqueurs.length == 1)
	 		{

	 			//Creation de la Google Map pour un seul marker
	 			maCarte = new google.maps.Map( document.getElementById("map"), 
	 			{
					zoom: 13,
					center: tableauMarqueurs[i],
					mapTypeId: google.maps.MapTypeId.ROADMAP
				});

 				//Creation du marqueur
	            marqueur = new google.maps.Marker(
	            {
					position: tableauMarqueurs[i],
					map: maCarte
				});
					
				//On recupere les info de chaque marker et on les insere dans la bulle d'info
				content = contentString[i];

	            //Et de son info-bulle 
				marqueur.addListener('click', (function(marqueur,content) 
		 		{  
		            return function() 
		            {  
			 			infowindow.setContent(content);
					    infowindow.open(maCarte, marqueur);
					} 
		        })(marqueur,content));

	        }
	        //Si il y a plusieurs marqueurs on créer un itineraire 
	        else if (tableauMarqueurs.length > 1)
	        {
	        	//Le premier marqueur devient le depart/origin de l'itineraire 
		        if (i == 0) 
	            {
					request.waypoints = [];
	                request.origin = marqueur.getPosition();

	            }
	            //Le dernier marqueur devient la destination de l'itineraire
	            else if (i == tableauMarqueurs.length - 1) 
	            {
					request.destination = marqueur.getPosition();
	            }
	            //Les autres deviennent des points de passages
	            else if (i != tableauMarqueurs.length - 1 || i != 0)
	            {
					request.waypoints.push(
					{
						location: marqueur.getPosition(),
						stopover: true
					});
	            }
	        }
		}

//-----------------------------------------------------------------------------------------------------------
		
		//Si il y a plus d'un marqueur
		if (tableauMarqueurs.length > 1)
		{

			//Creation de l'itinéraire 
			directionsService.route(request, function(result, status) {
		        if (status == google.maps.DirectionsStatus.OK) {
		            directionsDisplay.setDirections(result);
		        }
		    });
		
			//zoom sur tout les marqueurs afficher sur la map  
	    	maCarte.fitBounds( zoneMarqueurs );
	    }

//-----------------------------------------------------------------------------------------------------------
	</script>
</div>