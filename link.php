<?php


header("Content-type: application/json");


// if(!isset($_POST['post']) or empty($_POST['post'])) return;

//$_POST['post'] = "https://phppot.com/php/extract-content-using-php-and-preview-like-facebook/";

$urlPattern = "/(http|https)?(:\/\/)?([a-zA-Z0-9]\.)?[A-Za-z]+\.[\/a-zA-Z0-9-_=\?]+/m";

$link = preg_match($urlPattern, $_POST['post'], $m);

$res  = [
    "res" => false
];

if ($link) {
    $link = $m[0];
    $content = @file_get_contents($link,false, stream_context_create( [
        "http" => [
            "follow_location" =>true,
            "user_agent" => "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko)"
        ]
    ]));

    if (!$content) {
        $res[] = "Link Preview not available";
        echo  json_encode($res);
        exit;
    }

    $dom = new DOMDocument();
    @$dom->loadHTML($content);
    $meta =  $dom->getElementsByTagName("meta");
    $title = $dom->getElementsByTagName('title');
    $title = $title->item(0)->nodeValue;
    $img = $dom->getElementsByTagName("img");
    $img = $img->item(0)->getAttribute("src");
    $img  = ($img and filter_var($img, FILTER_VALIDATE_URL)) ?
        $img : "internet.png";
    $description = "";

    if ($meta) {
        for ($i = 0; $i < $meta->length; $i++) {
            $current = $meta->item($i);
            if ($current->getAttribute("name") == "description") {
                $description = $current->getAttribute("content");
                break;
            } else {
                $description = "Page decription not available";
            }
        }
    }

    if ($title) {
        $res = json_encode([
            "res" => true,
             "title" => $title, 
             "desc" => $description, 
             "img" => $img,
             "link" => $link
        ]);
        echo  $res;
    }
} else {
    $res[] = "Link Preview not available";
    echo  json_encode($res);
    exit;
}
