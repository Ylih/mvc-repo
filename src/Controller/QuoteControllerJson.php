<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class QuoteControllerJson
{
    #[Route("/api/quote")]
    public function jsonQuote(): Response
    {
        date_default_timezone_set('Europe/Stockholm');

        //Used to get quote from array.
        $number = random_int(0, 2);

        $day = date('l');
        $date = date('Y-m-d');
        $time = date('H:i:s');

        $quotes = [
            'Monday' => ["Måndagar är ett jäkla sätt att använda en sjundedel av sitt liv på.", "Måndag...", "En dålig dag är också bra!"],
            'Tuesday' => ["Varför kallas det rusningstid när allt står stilla?", "Jag är hemskt ledsen. Men om du bara hade haft rätt hade jag hållit med dig", "Politiker är som krokodiler; stora i käften och utan öron."],
            'Wednesday' => ["Halvvägs på jobbveckan!", "Snart fredag!", "Lillördag!"],
            'Thursday' => ["Snart helg!", "Sunt förnuft är som deodorant. De som behöver det som mest använder det aldrig.", "Jag älskar mänskligheten, det är bara människorna jag inte tål."],
            'Friday' => ["Freeeeeedag vilken underbar dag!", "Nu säger vi adjö till arbetsveckan som varit!", "Glöm ej commit och push precis innan hemgång!"],
            'Saturday' => ["Kruxet med goda ideér är att de snabbt urartar till hårt arbete", "Bara tråkiga människor briljerar på morgonen.", "Om din ovän förargar dig – ge trumpeter till hans barn."],
            'Sunday' => ["Sunday blues.", "Söndag... dagen jag planerar mycket men jag gör inte någonting.", "Idag är söndag och frågan uppstår, vad ska jag göra imorgon?"]
        ];

        $data = [
            'quote' => $quotes[$day][$number],
            'todays-date' => $date,
            'day' => $day,
            'time' => $time,
        ];

        //return new JsonResponse($data);

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );
        return $response;
    }
}
