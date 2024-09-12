<?php

use Illuminate\Database\Capsule\Manager as Capsule;


Capsule::schema()->table('ct_contents', function ($table) {
    $table->integer("themeID")->unsigned()->nullable();
    $table->foreign("themeID")->references("themeID")->on("ct_themes");
});

$topics = Capsule::table('ct_topics')
    ->select('topicID', 'themeID')
    ->get();

$topics_by_id = [];
foreach ($topics as &$t) {
    $topics_by_id[$t->topicID] = $t;
}

$contents = Capsule::table('ct_contents')
    ->select('contentID', 'topicID')
    ->whereNotNull('topicID')
    ->get();

foreach ($contents as &$content) {

    $themeID = $topics_by_id[$content->topicID]->themeID;

    Capsule::table('ct_contents')
        ->where('contentID', $content->contentID)  // Condition
        ->update(['themeID' => $themeID]);  // Update the value

}