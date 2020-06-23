<?php
error_reporting(0);
// ini_set('display_errors', '1');
// ini_set('display_startup_errors', '1');
// error_reporting(E_ALL);
// die();
function connect_mysql(){
    $servername = "mysql.hibots.com.br";
    $username = "hibots";
    $password = "total01";
    $dbname = "hibots";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }catch(PDOException $e){
        die;
    }
    return $conn;
}

function query($conn,$query){
  try{
        $statement = $conn->query($query);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }catch(Exception $e){
        return $e;
    }
}

function crawlerPage($url,$useragent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.89 Safari/537.36',$timeout = 12000){
  $dir = dirname(__FILE__);
  $cookie_file = $dir . '/cookies/' . md5($_SERVER['REMOTE_ADDR']) . '.txt';
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_FAILONERROR, true);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
  curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
  curl_setopt($ch, CURLOPT_ENCODING, "" );
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
  curl_setopt($ch, CURLOPT_AUTOREFERER, true );
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout );
  curl_setopt($ch, CURLOPT_TIMEOUT, $timeout );
  curl_setopt($ch, CURLOPT_MAXREDIRS, 10 );
  curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
  curl_setopt($ch, CURLOPT_REFERER, $url);
  return curl_exec($ch);
}

function carregar($url, $post = null, $access_token = null, $action = 'POST'){
      global $TEMPOCARREGAMENTO, $NUMEROCARREGAMENTO;

      $ini = microtime(true);
      $ch = curl_init();
      $head = array();
      curl_setopt($ch, CURLOPT_URL, $url);
      if (!is_null($post)) {
          curl_setopt($ch, CURLOPT_POST, true);
          curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
      }
      if (!is_null($access_token)) {
          $head[] = "X-Shopify-Access-Token: $access_token";
      }
      if (!is_null($post) && is_string($post)) {
          $head[] = "Content-Type: application/json";
      }
      if ($action != 'POST') {
          curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $action);
      }
      if (count($head) > 0)
          curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
      curl_setopt($ch, CURLOPT_HEADER, false);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

      curl_setopt($ch, CURLOPT_TIMEOUT, 90);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);


      $resp = curl_exec($ch);
      if ($resp === false) {
          echo curl_error($ch);
      }
      $respA = json_decode($resp, true);


      curl_close($ch);
      $TEMPOCARREGAMENTO = $TEMPOCARREGAMENTO + microtime(true) - $ini;
      $NUMEROCARREGAMENTO++;
      file_put_contents("url.txt", date('c') . " $url " . (@count($respA)) . " " . (@count(current($respA))) . "\r\n", FILE_APPEND);
      return $respA;
  }
function get($conn,$table,$clause,$order = '',$exp = ' and '){
      foreach(array_keys($clause) as $c){
      	$prepare_clause[] = " `$c` = ".$clause[$c];
      }
      $prepare = "select * from `$table` where ".implode($exp,$prepare_clause).' '.$order;
      // echo $prepare;
      return query($conn,$prepare);
}
function get_like($conn,$table,$clause,$order = '',$exp = 'and'){
      foreach(array_keys($clause) as $c){
      	$prepare_clause[] = " `$c` like '%".$clause[$c]."'%";
      }
      $prepare = "select * from `$table` where ".implode($exp,$prepare_clause).' '.$order;
      // echo $prepare;
      return query($conn,$prepare);
}
// error_reporting(0);

header('Content-Type: application/json');
$conn = connect_mysql();
$action = $_GET['action'];
unset($_GET['action']);
if($action == 'mudar_types'){
$vet = query($conn,'SELECT `typeName` FROM `calc_bicycle` WHERE `brandName` = "'.$_GET['brand'].'" and typeId in (1,5,42) group by `typeName`;');
header('Content-Type: text/html');
  echo '<div class="form-group" id="modelName_atr">
    <label>Selecione o Modelo:</label>
    </br>
    <select class="form-control" name="modelName" id="modelName" onchange="mudar_anos();">';
  foreach($vet as $y){
    echo '<option value="'.$y['typeName'].'">'.$y['typeName'].'</option>';
  }
  echo '</select>';
  die();
}else if($action == 'mudar_anos'){
$vet = query($conn,'SELECT last_year_fab as "yearId" FROM `calc_bicycle` WHERE `last_year_fab` > 2014 and name not like "%frameset%" and `typeName` = "'.$_GET['model'].'" and `brandName` = "'.$_GET['brand'].'" group by `yearId`;');
header('Content-Type: text/html');
  echo '<label>Ano:</label>
  <input type="hidden" class="form-control" name="page" value="1">
  </br>
  <select class="form-control" name="last_year_fab" id="last_year_fab">';
  foreach($vet as $y){
    echo '<option value="'.$y['yearId'].'">'.$y['yearId'].'</option>';
  }
  echo '</select>';
  die();
}else if($action == 'bicycle_des'){
  $vet = get($conn,'calc_bicycle',$_GET);
  echo json_encode($vet);
}else if($action == 'bicycle_form'){
  $vet['models'] = query($conn,'SELECT typeName as modelName FROM `calc_bicycle` where typeId in (1,5,42) group by typeName;');
  $vet['brands'] = query($conn,'SELECT brandName FROM `calc_bicycle` group by brandName;');
  echo json_encode($vet);
}else if($action == 'bicycle_pesquisa'){
  // $vet = query($conn,'SELECT * FROM `calc_bicycle` where name like "%'.$_GET['pesquisa'].'%" and last_year_fab > 2015;');
  $vet = query($conn,'SELECT * FROM `bicycle` where name like "%'.$_GET['brandName'].'%" and yearId > 2014 and name not like "%frameset%"
  and brandId in (672,1002,737,741,750,800,900) and typeId in (1,5,42) order by yearId desc;');
  // var_dump($vet);
  if(count($vet)==0){
    $pesquisa_vet = explode(' ',$_GET['pesquisa']);
    foreach($pesquisa_vet as $pv){
      $prepare_clause[] = " `name` like '%".$pv."%'";
    }
    $vet = query($conn,"select * from `bicycle` where ".implode(" and ",$prepare_clause).' and name not like "%frameset%"
    and brandId in (672,1002,737,741,750,800,900) and typeId in (1,5,42) order by yearId desc;');
  }
  if(count($vet)==0){
    $pesquisa_vet = explode(' ',$_GET['pesquisa']);
    foreach($pesquisa_vet as $pv){
      $prepare_clause[] = " `name` like '%".$pv."%'";
    }
    $vet = query($conn,"select * from `bicycle` where (".implode(" or ",$prepare_clause).') and name not like "%frameset%"
    and brandId in (672,1002,737,741,750,800,900) and typeId in (1,5,42) order by yearId desc;');
  }
  echo json_encode($vet);
}else if($action == 'bicycle_list'){
  // print_r($_GET);
  $vet = get($conn,'bicycle',$_GET,'order by yearId desc');
  echo json_encode($vet);
}else if($action == 'bicycle_details'){
  $vet = query($conn,"SELECT * FROM `bicycle` where id = ".$_GET['id_bicycle'].";")[0];
  $vet['components'] = query($conn,"SELECT * FROM `bicycle_components` where `bicycle_id` = ".$_GET['id_bicycle'].";");
  $vet['additionalYears'] = query($conn,'SELECT * FROM `bicycle` where `modelId` = (select `modelId` from bicycle where id = '.$_GET['id_bicycle'].') and id <> '.$_GET['id_bicycle'].';');
  echo json_encode($vet);
}else if($action == 'bicycle_desvalorizacao'){
  // $vet = query($conn,'SELECT *,(`retailPrice`-(`retailPrice`*(select (`Ano_'.$_GET['year_bicycle'].'`/100) from bike_desvalorizacao2 bd where c.brandName = bd.road ))) as bike_value FROM `calc_bicycle` c where id = '.$_GET['id_bicycle'].';');
  // $vet = query($conn,'SELECT c.*,bc.value as \'Taxa de Conversao\',(c.retailPrice*bc.value) as \'Preco Brasil\',
  // ((`retailPrice`-(`retailPrice`*(select (`Ano_0`/100) from bicycle_desvalorizacao2 bd where c.`brandId` = bd.`brandId` and  c.`typeId` = bd.`typeId`)))*bc.value) as \'Ano 0\',
  // ((`retailPrice`-(`retailPrice`*(select (`Ano_1`/100) from bicycle_desvalorizacao2 bd where c.`brandId` = bd.`brandId` and  c.`typeId` = bd.`typeId`)))*bc.value) as \'Ano 1\',
  // ((`retailPrice`-(`retailPrice`*(select (`Ano_2`/100) from bicycle_desvalorizacao2 bd where c.`brandId` = bd.`brandId` and  c.`typeId` = bd.`typeId`)))*bc.value) as \'Ano 2\',
  // ((`retailPrice`-(`retailPrice`*(select (`Ano_3`/100) from bicycle_desvalorizacao2 bd where c.`brandId` = bd.`brandId` and  c.`typeId` = bd.`typeId`)))*bc.value) as \'Ano 3\',
  // ((`retailPrice`-(`retailPrice`*(select (`Ano_4`/100) from bicycle_desvalorizacao2 bd where c.`brandId` = bd.`brandId` and  c.`typeId` = bd.`typeId`)))*bc.value) as \'Ano 4\',
  // ((`retailPrice`-(`retailPrice`*(select (`Ano_5`/100) from bicycle_desvalorizacao2 bd where c.`brandId` = bd.`brandId` and  c.`typeId` = bd.`typeId`)))*bc.value) as \'Ano 5\'
  // FROM `calc_bicycle` c inner join bicycle_desvalorizacao2 dv on dv.`brandId` = c.`brandId` and dv.`typeId` = c.`typeId` inner join bike_conversao bc on bc.brandId = c.`brandId` and bc.typeId = c.`typeId` where c.id = '.$_GET['id_bicycle'].';');
  // $vet = query($conn,'SELECT c.*,bc.value as \'Taxa de Conversao\',(c.retailPrice*bc.value) as \'Preco Brasil\',
  // ((`retailPrice`-(`retailPrice`*(select (`Ano_0`/100) from bicycle_desvalorizacao2 bd where c.`brandId` = bd.`brandId` and  c.`typeId` = bd.`typeId`)))*bc.value) as \'Ano 0\',
  // ((`retailPrice`-(`retailPrice`*(select (`Ano_1`/100) from bicycle_desvalorizacao2 bd where c.`brandId` = bd.`brandId` and  c.`typeId` = bd.`typeId`)))*bc.value) as \'Ano 1\',
  // ((`retailPrice`-(`retailPrice`*(select (`Ano_2`/100) from bicycle_desvalorizacao2 bd where c.`brandId` = bd.`brandId` and  c.`typeId` = bd.`typeId`)))*bc.value) as \'Ano 2\',
  // ((`retailPrice`-(`retailPrice`*(select (`Ano_3`/100) from bicycle_desvalorizacao2 bd where c.`brandId` = bd.`brandId` and  c.`typeId` = bd.`typeId`)))*bc.value) as \'Ano 3\',
  // ((`retailPrice`-(`retailPrice`*(select (`Ano_4`/100) from bicycle_desvalorizacao2 bd where c.`brandId` = bd.`brandId` and  c.`typeId` = bd.`typeId`)))*bc.value) as \'Ano 4\',
  // ((`retailPrice`-(`retailPrice`*(select (`Ano_5`/100) from bicycle_desvalorizacao2 bd where c.`brandId` = bd.`brandId` and  c.`typeId` = bd.`typeId`)))*bc.value) as \'Ano 5\'
  // FROM `bicycle` c inner join bicycle_desvalorizacao2 dv on dv.`brandId` = c.`brandId` and dv.`typeId` = c.`typeId` inner join bike_conversao bc on bc.brandId = c.`brandId` and bc.typeId = c.`typeId` where c.id = '.$_GET['id_bicycle'].';');
  $vet = query($conn,'SELECT * FROM `bicycle_cotacao` WHERE `id` = '.$_GET['id_bicycle'].';');
  echo json_encode($vet[0]);
}else{
  echo json_encode(['error'=>true,'message'=>'Ação não listada']);
}

?>
