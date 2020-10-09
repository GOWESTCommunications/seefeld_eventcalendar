<?php
    namespace GOWEST\EventsSeefeld\Task;

    class Task extends \TYPO3\CMS\Scheduler\Task\AbstractTask {

        public function execute() {

            $user = "ws.gowest";
            $key = "58wa11fd";
            $date = date("Y-m-d");
            // $url = "https://seefeld.infomaxnet.de/imxplatformj/api?user=" . $user . "&password=" . $key . "&method=FindEventIds&eStartDate=" . $date . "&eStateIds=40&eOrderFields=DATE-%5BDESC%5D&imxFormat=json";

            $ch = curl_init();

            //Request der alle EventIds ab des derzeitigen Tages zurückgibt.Nur veröffentlichte Events, sortiert nach Datum aufsteigend und im JSON Format.
            curl_setopt($ch, CURLOPT_URL, "https://seefeld.infomaxnet.de/imxplatformj/api?user=" . $user . "&password=" . $key . "&method=FindEventIds&eStartDate=" . $date . "&eStateIds=40&eOrderFields=DATE-[ASC]&imxFormat=json");
            // curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);    

            $response = curl_exec($ch);

            if($response === FALSE){
                die(curl_error($ch));
            }

            $jsonObj = json_decode($response, true);
            curl_close($ch);

            $array;
            $eventCount = count($jsonObj['eventIds']);


            for ($i = 0; $i < $eventCount; $i++) {
                $ch = curl_init();

                $id = ($jsonObj['eventIds'][$i]['id']);

                if($id != null) {
                    curl_setopt($ch, CURLOPT_URL, "https://seefeld.infomaxnet.de/imxplatformj/api?user=" . $user . "&password=" . $key . "&method=FindEvent&objectId=" . $id . "&imxFormat=json");
                    // curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
                    
                    $response = curl_exec($ch);
                    $jsonData = json_decode($response)->Event;

                    foreach ($jsonData->media as $media) {
                        if ($media->_entityType == "EventImage") {
                            $image = $media->deeplink;
                            break;
                        }
                    }

                    $array[$i] = json_encode(array(
                        'name' => $jsonData->title->de,
                        // 'longDescription' => $jsonData->longDescription->de,
                        'fromDate' => $jsonData->eventDateType->startDate,
                        'toDate' => $jsonData->eventDateType->endDate,
                        // 'specificDates' => $jsonData->eventDateType->specificEventDates,
                        'image' => $image,
                        // 'contactName' => $jsonData->contributor->contact1->contactName,
                        // 'email' => $jsonData->contributor->contact1->address->email,
                        // 'number' => $jsonData->contributor->contact1->address->phone1,
                        'city' => $jsonData->contributor->location->name,
                        'homepage' => $jsonData->contributor->contact1->address->homepage->de,
                        'freeOfCharge' => $jsonData->pricing->freeOfCharge,
                    ));
                    // \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($array[$i]);
                }
            }

            $json = json_encode($array);


            $res = file_put_contents("/data/www/grandhotel/typo3temp/events/events_seefeld.json", $json);
            // \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($jsonData);
            // exit;
            return $jsonObj;
         }
    }
?>