<html>
	<head>
		<meta charset="utf-8">
		<title>Liste de resultat</title>
		<link rel="stylesheet" type="text/css" href="style.css" />
	</head>
	<body>
		<br><br><br>
	<?php
	//récuperer dans des variables locales les parametres du formulaire
	$nomGene = $_REQUEST['nomgene'];
	$nomPro = $_REQUEST['nompro'];
	$comment = $_REQUEST['comment'];

	//Si tous les trois champs sont vides
	if(empty($nomGene) && empty($nomPro) && empty($comment)){
	echo '<p>Vous devez remplir au moins un champ</p> 
			<p>Cliquez <a href="index.html">ici</a> pour revenir</p>';
	}else{//sinon
	//On connecte à la base de données
	$connexion = oci_connect('c##nguo_a','nguo_a','dbinfo');

	echo '<br> <h2> Liste des proteines correspondantes à la recherche :  </h2>';

	$txtReq="SELECT DISTINCT accession FROM entry_2_gene_name e,gene_names g WHERE g.gene_name LIKE '%".$nomGene."%'
			AND accession IN(SELECT DISTINCT pn.accession 
							FROM prot_name_2_prot pn, protein_names p WHERE p.prot_name LIKE '%".$nomPro."%' 
							AND pn.accession IN(SELECT DISTINCT accession 
											 	FROM comments c WHERE c.txt_c LIKE '%".$comment."%' 
												)
							)"; 

	$ordre = oci_parse($connexion, $txtReq);
	oci_execute($ordre);
	echo'<table border="1">';
	echo'<tr><th> Accession </th></tr>';
	while(($row= oci_fetch_array($ordre, OCI_BOTH))!=false){
		echo '<tr><td><form method = "post" action = "accession.php">
   				<input type = "submit" name = "accession" value ="'.$row[0].'" class="btn">
			  </form></td></tr>';
	}
	echo'</table>';

	oci_free_statement($ordre);

	}
	oci_close($connexion);

	?>
	</body>
<html>