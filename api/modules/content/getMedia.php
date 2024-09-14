<?php

$duration = 5; // Durata dei link s3

if (isset($d["media"])) {
    $media = json_decode($d["media"], true);
} else {
    $content = $this->db->sql_select("SELECT media FROM ct_contents WHERE permalink = ?",$d["permalink"]);
    $media = json_decode($content[0]["media"],true);
}

$out = [];

if (isset($media["mediaType"]) && count(array_keys($media[$media["mediaType"]."_data"])) > 0) {

    $out["mediaType"] = $media["mediaType"];

    if ($media["mediaType"] == "pdf") {
        $out["pdf_data"] = [
            "url" => $this->run("core/s3/get", ["key" => "documents/".$d["permalink"].".pdf", "duration" => $duration])["url"],
            "expire" => (new DateTime())->modify("+$duration minutes")->format(DateTime::ATOM),
        ];
    }

    if ($media["mediaType"] == "audio") {
        $out["audio_data"] = [
            "url" => $this->run("core/s3/get", ["key" => "audio/".$d["permalink"].".mp3", "duration" => $duration])["url"],
            "expire" => (new DateTime())->modify("+$duration minutes")->format(DateTime::ATOM),
        ];
    }

    if ($media["mediaType"] == "vimeo") {
        $out["vimeo_data"] = [
            "video_id" => $media["vimeo_data"]["video_id"],
        ];
    }

    if ($media["mediaType"] == "embed") {
        $out["embed_data"] = [
            "url" => $media["embed_data"]["url"],
        ];
    }

    if ($media["mediaType"] == "quiz") {

        $corrette = [];
        $questions = [];

        foreach ($media["quiz_data"]["questions"] as $i=>$question) {
            $nr = count($question["answers"]);

            $ids = range(0, $nr-1);

            shuffle($ids);

            $risposte_casuali = [];

            foreach ($ids as &$id) {
                $risposte_casuali[] = $question["answers"][$id];
            }

            $corrette[] = array_search(0,$ids);
            $questions[] = [
                "question" => $question["question"],
                "answers" => $risposte_casuali
            ];

        }

        $responseID = $this->db->insertInto("qz_quiz_responses",[
            "IDutente" => $this->user["IDutente"],
            "permalink" => $d["permalink"],
            "correct_answers" => $corrette
        ]);

        $out["quiz_data"] = [
            "duration" => $media["quiz_data"]["duration"],
            "threshold" => $media["quiz_data"]["threshold"],
            "questions" => $questions,
            "responseID" => $responseID,   
        ];
    }


}



return $out;