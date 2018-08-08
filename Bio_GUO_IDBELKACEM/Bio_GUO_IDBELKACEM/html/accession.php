<html>
	<head>
		<meta charset="utf-8">
		<title>Informations</title>
		<link rel="stylesheet" type="text/css" href="style.css" />
	</head>
	<body>
		<br><br><br>
	<?php

	//Récupérer dans une variable locale le paramètre "accession" du formulaire
	$ac = $_REQUEST['accession'];
	
	//afficher l'accession 
	echo '<h1>'.$ac.'</h1>';
	
	//Connexion à la base de données 
	$connexion = oci_connect('c##nguo_a','nguo_a','dbinfo');

	//afficher le table de sequence et espèce
	echo '<br><table>';

	$txtReq = "SELECT p.seq, p.seqLength, p.seqMass, e.specie FORM proteins p,entries e 
			WHERE p.accession = :acces AND e.accession = :acces ";

	$ordre = oci_parse($connexion,$txtReq);

	//Associer la variable PHP $ac avec le paramètre Oracle :acces
	oci_bind_by_name($ordre,":acces",$ac);
	oci_execute($ordre);

	while(($row = oci_fetch_array($ordre,OCI_BOTH))!=false)
	{
		 echo'<tr><td>Sequence</td><td>'.$row[0].'</td></tr>';
		 echo'<tr><td>Length de la sequence</td><td>'.$row[1].'</td></tr>';
		 echo'<tr><td>Mass de la sequence</td><td>'.$row[2].'</td></tr>';
		 echo'<tr><td>Espèce</td><td><a href = "https://www.ncbi.nlm.nih.gov/Taxonomy/Browser/wwwtax.cgi?id='.$row[3].'"> numeros de taxonomy NCBI : '.$row[3].' </a></td></tr>';
	}
	echo '</table>'
 	oci_free_statement($ordre);

	
	//afficher le tableau de protéine
	echo '<br><h3>Les protéines</h3>';
	echo'<table border="1">';

	$txtReqProt = "SELECT prot_name , name_kind, name_type FROM protein_names p , prot_name_2_prot pp, entries e 
			WHERE p.prot_name_id = pp.prot_name_id AND pp.accession = e.accession AND  e.accession = :acces ";

	$ordreProt = oci_parse($connexion,$txtReqProt);
	oci_bind_by_name($ordreProt,":acces",$ac);
	oci_execute($ordreProt);

	echo'<tr><th> Nom de protéine </th><th> Type de nom </th><th> type </th></tr>';
	while(($rowProt= oci_fetch_array($ordreProt, OCI_BOTH))!=false){
		echo'<tr>'.'<td>'.$rowProt[0].'</td><td>'.$rowProt[1].'</td><td>'.$rowProt[2].'</td></tr>';
	}
	echo'</table>';
	oci_free_statement($ordreProt);


	//afficher le tableau des noms de gène
	echo '<br><h3>Les gènes</h3>';
	echo'<table border="1">';

	$txtReqGene = "SELECT gene_name ,name_type FROM gene_names g , entry_2_gene_name eg, entries e 
			WHERE g.gene_name_id = eg.gene_name_id AND eg.accession = e.accession AND  e.accession = :acces ";

	$ordreGene = oci_parse($connexion,$txtReqGene);
	oci_bind_by_name($ordreGene,":acces",$ac);
	oci_execute($ordreGene);

	echo'<tr><th> Nom de gène </th><th> Type de nom </th></tr>';
	while(($rowGene= oci_fetch_array($ordreGene, OCI_BOTH))!=false){
		echo'<tr>'.'<td>'.$rowGene[0].'</td><td>'.$rowGene[1].'</td></tr>';
	}
	echo'</table>';
	oci_free_statement($ordreGene);

	//Afficher les mots-clés
	echo '<br><h3>Les mots-clés </h3>';

	$txtReqKey = " SELECT kw_label FROM keywords k, entries_2_keywords ek, entries e 
			WHERE k.kw_id = ek.kw_id AND ek.accession = e.accession AND  e.accession = :acces ";

	$ordreKey = oci_parse($connexion,$txtReqKey);
	oci_bind_by_name($ordreKey, ":acces", $ac);
	oci_execute($ordreKey);
	while (($rowKey= oci_fetch_array($ordreKey, OCI_BOTH))!=false){
		echo '<br>'.$rowKey[0];
	}
	oci_free_statement($ordreKey);

	//afficher les commentaires
	echo '<br><h3> commentaires : </h3>';

	$txtReqCom = "SELECT type_c, txt_c FROM comments c WHERE c.accession = :acces ";

	$ordreComment = oci_parse($connexion,$txtReqCom);
	oci_bind_by_name($ordreComment, ":acces", $ac);
	oci_execute($ordreComment);
	while (($rowCom = oci_fetch_array($ordreComment, OCI_BOTH))!=false){
		echo '<p style="font-size:20px; font-weight: bold;">'.$rowCom[0].'</p>'.$rowCom[1].'<br>';
	}
	oci_free_statement($ordreComment);

	//Afficher les liens relatives aux termesGO
	echo '<br><h3>Liens vers la base de données </h3>';

	$txtReqRef = "SELECT db_ref FROM dbref WHERE accession = :acces ";

	$ordreRef = oci_parse($connexion,$txtReqRef);
	oci_bind_by_name($ordreRef, ":acces", $ac);
	oci_execute($ordreRef);
	$i=0;
	while (($rowRef =  oci_fetch_array($ordreRef, OCI_BOTH))!=false){
		if($i>=13){
			$i=0;
			echo '<br><br>';
		}
		echo '<a href = https://www.ebi.ac.uk/QuickGO/term/'.$rowRef[0].'>'.$rowRef[0].'</a> ';
		$i=$i+1;
	}
	oci_free_statement($ordreGo);	

oci_close($connexion);
?>

</body>
</html>






