<?php
@session_start();

$call = new \Curls\Curls();
$headers = [
    "Accept: application/json",
    "X-API-Key: lAYa8FK2x3O0iNnSFa2c5o2XPAWiK8E2"
];

$call->setHeaders($headers);
$call->setBaseUrl('https://adultvideosapi.com/api');
$call->runCurl('videos/get-all');
$data = $call->getResultBody();

$colls = ['vid', 'title', 'groups', 'vurl', 'vpreview', 'vthumbnail', 'vsource', 'vsourceid'];
$cols2 = ['vid', 'duration', 'votes','views','rating','likes', 'dislikes'];

$attr1 = [
    'vid'=>['INT(11)', 'AUTO_INCREMENT', 'PRIMARY KEY'],
    'title'=>['VARCHAR(150)','NOT NULL'],
    'groups'=>['VARCHAR(100)','NULL'],
    'vurl'=>['VARCHAR(200)','NOT NULL'],
    'vpreview'=>['VARCHAR(250)','NULL'],
    'vthumbnail'=>['VARCHAR(250)', 'NULL'],
    'vsource'=>['VARCHAR(100)', 'NULL'],
    'vsourceid'=>['INT(11)', 'NOT NULL']
];

$attr2 = [
    'vid'=>['INT(11)', 'NOT NULL'],
    'duration'=>['VARCHAR(20)', 'NULL'],
    'votes'=>['VARCHAR(20)', 'NULL'],
    'views'=>['VARCHAR(20)', 'NULL'],
    'rating'=>['VARCHAR(20)', 'NULL'],
    'likes'=>['VARCHAR(20)', 'NULL'],
    'dislikes'=>['VARCHAR(20)', 'NULL']
];

$maker = new \Datainterface\MysqlDynamicTables();
$con = \Datainterface\Database::database();
$maker->resolver($con, $colls, $attr1, 'videos',false);
$maker->resolver($con, $cols2, $attr2, 'videosmeta', true);

$savedVideos = [];

if(!empty($data['data'])){

    foreach ($data['data'] as $video){
        extract($video);

        $oldvideo = \Datainterface\Selection::selectById('videos', ['vsourceid'=>$source_id]);
        if(empty($oldvideo)){
            $datarow = ['title'=>$title,
                'groups'=>$title_alphabet,
                'vurl'=>$embed_url,
                'vpreview'=>$preview_url,
                'vthumbnail'=>$default_thumb_url,
                'vsource'=>$source,
                'vsourceid'=>$source_id];
            $vid = \Datainterface\Insertion::insertRow('videos', $datarow);

            $metadata = ['vid'=>$vid,
                'duration'=>$duration,
                'votes'=>$votes_count,
                'views'=>$views_count,
                'rating'=>$rating,
                'likes'=>$votes_up,
                'dislikes'=>$votes_down];
            $rowid = \Datainterface\Insertion::insertRow('videosmeta', $metadata);

            array_push($savedVideos, $rowid.'-new video-'.$embed_url);
        }else{
            array_push($savedVideos, $oldvideo[0]['vid'].'-old video-'.$embed_url);
        }
    }
}

?>

<ul>
    <?php foreach ($savedVideos as $vsaved): ?>
      <li><?php echo $vsaved; ?></li>
    <?php endforeach; ?>
</ul>
