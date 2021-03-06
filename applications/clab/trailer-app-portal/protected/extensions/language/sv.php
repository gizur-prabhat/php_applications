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
    'Assets' => 'inventarie',
    'Assets List' => 'inventarie Lista',
    'Asset No' => 'inventarie nr',
    'Asset Name' => 'inventarie Namn',
    'Customer Name' => 'Kundens namn',
    'Product Name' => 'Produktnamn',
    'Serial Number' => 'SERIENUMMER',
    'Action' => 'Åtgärd',
    'edit' => 'redigera',
    'Create New Asset' => 'Skapa nya inventarie',
    'Type of Trailer' => 'Typ av Trailer',
    '-- Select --' => '- Välj -',
    'Date Sold' => 'Datum såld',
    'Date in Service' => 'Datum inom Service',
    'Shipping Method' => 'Leveransmetod',
    'Shipping Tracking Number' => 'Fraktspårningsnummer',
    'Status' => 'Status',
    'Notes' => 'Anmärkningar',
    'Save' => 'Spara',
    'Cancel' =>'Avbryt',
    'Update' => 'Uppdatera',
    'Contacts' => 'Kontakter',
    'Contacts List' => 'Kontakter',
    'First Name' => 'förnamn',
    'Last Name' => 'EFTERNAMN',
    'Email' => 'Email',
    'Organization' => 'Organisation',
    'Search' => 'Sökning',
    'Contact Id' => 'Kontakta Id',
    'Title' => 'Titel',
    'Organization Name' => 'Organisationens namn',
    'Office Phone' =>'Växel',
    'Assigned To' => 'Tilldelad',
    'Reset Password' => 'Återställ lösenord',
    'Create New Contact' => 'Skapa ny kontakt',
    'add' => 'lägg',
    'Mobile' => 'Mobil',
    'Assistant' => 'assistent',
    'Reports To' => 'Rapporter till',
    'Assistant Phone' => 'Assistant Telefonnummer',
    'Customer Portal Information' => 'Kundportal Information',
    'Portal User' => 'Portal Användar',
    'Support Start Date' => 'Support Startdatum',
    'Support End Date' => 'Stöd Slutdatum',
    'Address Information' => 'Adress Information',
    'Mailing Street' => 'Mailing Street',
    'Mailing PO Box' => 'Mailing PB',
    'Mailing City' => 'Mailing Stad',
    'Mailing State' => 'Mailing State',
    'Mailing Postal Code' => 'Mailing postnummer',
    'Mailing Country' => 'Mailing Land',
    'Description Information' => 'Beskrivning Information',
    'Description' => 'Beskrivning',
    'Update Contact' => 'Uppdatera Kontakt',
    'Are you sure to delete this data?' => 'Är du säker på att ta bort dessa data?',
    'Update Asset' => 'Uppdatera inventarie',
    'Are you sure to reset password?' => 'Är du säker på att återställa lösenord?',
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
