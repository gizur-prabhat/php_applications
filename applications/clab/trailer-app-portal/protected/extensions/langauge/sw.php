<?php
global $langaugeArr;
$langaugeArr = array (
'Trailer App Portal' => 'Trailer portal',
'Date' => 'Datum',
'Time' => 'Tid',
'Account' => 'Transportör',
'Contact' => 'Chaufför',
'Damage Reported' => 'Raporterat skada',
'In operation' => 'I drift',
'Damaged' => 'På reparation',
'Ticket ID' => 'Ärendenummer',
'Trailer ID' => 'Trailer ID',
'Status of damage' => 'Status för ärende',
'Mark damage repaired' => 'Stäng skadeärende',
'Pictures' => 'Bilder',
'Driver caused damage' => 'Rapporterande chaufför orsakade skadan',
'Type of damage' => 'Typ av skada',
'Position on trailer for damage' => 'Position på Trailer för skada',
'Upload Pictures' => 'Ladda upp Bilder',
'Create Date' => 'Skapa Datum',
'Ticket Category' => 'Ticket Kategori',
'Location for damage report' => 'Plats för Rapportera skada',
/*  For Mobile app  */
'Report Damage' => 'Rapportera skada',
'Reset' => 'Nollställ',
'Submit' => 'Skicka',
'Back' => 'Tillbaka',
'Cancel' => 'Avbryt' ,
'Survey' => 'Kontrollbesiktning',
'Trailer type' => 'Typ av trailer',
'Own' => 'Egen',
'Rented' => 'Hyrtrailer' ,
'ID' => 'ID',
'Place' => 'Plats',
'Sealed' => 'Plomberad',
'Yes' => 'Ja',
'No' =>  'Nej',
'Plates' => 'Skivor',
'Straps' => 'Spännband',
'Reported Damages' => 'Rapporterade skador',
'Report a new damage' => 'Rapportera ny skada',
'Type' => 'Typ',
'Position' => 'Position',
'Position on trailer' => 'Position på Trailer',
/*  Others  */
'Total rows' => 'Totalt rader',
'Rows' => 'rader', 
'Create new Trouble ticket' => 'Skapa nytt supportärende',
'Trouble ticket' => 'supportärende',
'Trouble ticket List' => 'Supportärende Lista',
'Login' => 'inloggning',
'Logout' => 'Logga',
'About' => 'om',
'Welcome' => 'Välkommen'
);
function getTranslatedString($str)
{
	global $langaugeArr;
	return (isset($langaugeArr[$str]))?$langaugeArr[$str]:$str;
 }
?>
