<?php
$id = $_GET["id"];
if ($id == ""){
  echo 'lütfen youtube id giriniz !';
  exit;
}
parse_str(file_get_contents('http://www.youtube.com/get_video_info?video_id='.$id), $video_data);
$streams = $video_data['url_encoded_fmt_stream_map'];
$streams = explode(',',$streams);
$counter = 1;
foreach ($streams as $streamdata) {
  if($counter == 3){
  	parse_str($streamdata,$streamdata);
  	foreach ($streamdata as $key => $value) {
  		if ($key == "url") {
  			$value = urldecode($value);
        touch($id.'.mp4');
        $dosya = fopen($id.'.mp4', 'w');
        fwrite($dosya, file_get_contents($value));
        fclose($dosya);
        exec('ffmpeg -i '.$id.'.mp4 -f mp3 -ab 192000 -vn '.$id.'.mp3');
        unlink($id.'.mp4');
        echo '<a href="'.$id.'.mp3">indirileni aç</a>';
      }
  	}
  }
  $counter = $counter+1;
}
