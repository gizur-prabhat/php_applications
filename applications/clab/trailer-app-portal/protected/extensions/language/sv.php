<?php

global $languageArr;
$languageArr = array(
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
    'Plates' => 'Skivor',
    'Straps' => 'Spännband',
    /*  For Mobile app  */
    'Report Damage' => 'Rapportera skada',
    'Reset' => 'Nollställ',
    'Submit' => 'Skicka',
    'Back' => 'Tillbaka',
    'Cancel' => 'Avbryt',
    'Survey' => 'Kontrollbesiktning',
    'Trailer type' => 'Typ av trailer',
    'Own' => 'Egen',
    'Rented' => 'Hyrtrailer',
    'ID' => 'ID',
    'Place' => 'Plats',
    'Sealed' => 'Plomberad',
    'Yes' => 'Ja',
    'No' => 'Nej',
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
    'Logout' => 'Logga ut',
    'About' => 'om',
    'Welcome' => 'Välkommen',
    'Status of damage' => 'Status för skador',
    'Damage' => 'skada',
    'Change Password' => 'Ändra lösenord',
    'Old Password' => 'Gammalt lösenord',
    'Enter old password' => 'Ange gammalt lösenord',
    'New Password' => 'Nytt lösenord',
    'Enter new password' => 'Ange nytt lösenord',
    'Confirm Password' => 'Bekräfta lösenord',
    'Confirm new password' => 'Bekräfta nytt lösenord',
    /* Custom Variables */
    'Anteckningar' => 'Anteckningar',
    'Damage Status' => 'Skador Status',
    'Ej påbörjat' => 'Ej påbörjat',
    'Under utredning' => 'Under utredning',
    'Väntar på kompletterande uppgifter' => 'Väntar på kompletterande uppgifter',
    'Ärende stängt' => 'Ärende stängt'
);

function getTranslatedString($str)
{
    global $languageArr;
    return (isset($languageArr[$str])) ? $languageArr[$str] : $str;
}

?>
