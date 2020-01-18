<?php

$datetime = 
    DateTime::createFromFormat(DateTimeInterface::RFC3339, 
        "2002-01-02T10:00:00-05:00");
   
echo $datetime->getTimezone()->getName()   . "</br>"; 
$datetime->setTimezone(new DateTimeZone('Europe/Amsterdam'));        

echo $datetime->getTimezone()->getName();        
echo $datetime->format('H:i:00');

?>