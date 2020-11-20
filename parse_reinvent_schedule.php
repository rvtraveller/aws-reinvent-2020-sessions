<?php

require_once('./vendor/autoload.php');

use RedBeanPHP\R;

R::setup('mysql:host=127.0.0.1;port=32769;dbname=reinvent', 'root', '');

$json = file_get_contents('./reinvent_sessions.json');

$parsed_json = json_decode($json);

$sessions = $parsed_json->sessions;

foreach ($sessions as $session) {
  $s = R::dispense('session');
  $s->awsRef = $session->id;
  $s->name = $session->name;
  $s->description = $session->description;
  $s->type = $session->type;
  $s->updatedAt = $session->updatedAt;
  $s->thumbnailUrl = $session->thumbnailUrl;
  R::tag($s, explode(',', $session->tags));
  R::tag($s, explode(',', $session->hiddenTags));
  $s->startTime = $session->schedulingData->start->timestamp;
  $s->endTime = $session->schedulingData->end->timestamp;

  R::store($s);

}

 ?>
