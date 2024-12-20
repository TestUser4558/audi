<?php
$pdo = require $_SERVER['DOCUMENT_ROOT'] . "/audi/db.php";
$req1 = $pdo->prepare('insert into brand(id, name, url, bold) values(?, ?, ?, ?)');
$req2 = $pdo->prepare('insert into models(brand_id, name, url, has_Panorama) values(?, ?, ?, ?)');
$req3 = $pdo->prepare('insert into generations(model_id, title, url, src, src2x, generationInfo, isNewAuto, isComingSoon, frames, sgroup, sgroupSalug, sgroupShort) values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
$req4 = $pdo->prepare('insert into images(generations_id, url) values(?, ?)');

$req_id1 = $pdo->prepare('select id from models where name = ?'); 
$req_id2 = $pdo->prepare('select id from generations where title = ?'); 

     $data = file_get_contents('Audi.json');
     $json = json_decode($data, true);
     $id = $json['id'];
     $br_name = $json['name'];
     $br_url = $json['url'];
     $bold = $json['bold'];

     $req1->execute([$id, $br_name, $br_url, $bold]);
     foreach ($json['models'] as $mod){
         $mod_name = $mod['name']  ;
         $mod_url = $mod['url']  ;
         $hasPanorama = $mod['hasPanorama'] ;

         $req2->execute([$id, $mod_name, $mod_url, $hasPanorama]);
         $req_id1->execute([$mod_name]);
         $mod_id = $req_id1->fetch(PDO::FETCH_ASSOC);
         $mod_id = (int)$mod_id['id'];

         foreach ($mod['generations'] as $gen) {
           $title = $gen['title'] ;
           $src = $gen['src'] ;
           $src2x= $gen['src2x'] ;
           $gen_url = $gen['url'] ;
           $gen_info = $gen['generationInfo'] ;
           $isNewAuto = $gen['isNewAuto'] ;
           $isComingSoon = $gen['isComingSoon'] ;
           $frames= $gen['frames'] ;
           $group= $gen['group'] ;
           $groupSalug = $gen['groupSalug'] ;
           $groupShort = $gen['groupShort'] ;

          $req3->execute([$mod_id, $title, $gen_url, $src, $src2x, $gen_info, $isNewAuto, $isComingSoon, $frames, $group, $groupSalug, $groupShort]);
          $req_id2->execute([$title]);
          $gen_id = $req_id2->fetch(PDO::FETCH_ASSOC);
          $gen_id = $gen_id['id'];

           foreach ($gen['images'] as $image) {

              $req4->execute([$gen_id, $image]);
           }
         }
     }


