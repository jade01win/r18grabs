<?php
namespace App\Controllers;
use GuzzleHttp\Client as httpClient;
use Illuminate\Database\Eloquent\Collection;
use Seld\JsonLint\JsonParser;
require_once "simplehtmldom/simplehtmldom.php";

class Home extends BaseController
{
  public function index() {
    return view('hterm');
  }
  public function limit_time(){
    $page ="1";
    $options = array(
      'http'=>array(
        'method'=>"GET",
        'header'=>"Accept-language: en\r\n" .
                  "Cookie: foo=bar\r\n" .  // check function.stream-context-create on php.net
                  "User-Agent: Mozilla/5.0 (iPad; U; CPU OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B334b Safari/531.21.102011-10-16 20:23:10\r\n" // i.e. An iPad 
      )
    );
    $context = stream_context_create($options);
    $client = new httpClient();
    $destdir = ROOTPATH.'/public/imgprv/';
    if(!file_exists($destdir)){
      mkdir($destdir);
    }
    $res = $client->request('GET', 'https://www.r18.com/videos/vod/movies/list/?id=6565&type=category&page='.$page);
    $shtml = str_get_html($res->getBody());
    for ($i = 1; $i < 30; $i++) {
      // $namev = $shtml->find('li.item-list is-sale', $i)->plaintext;
      // $name = str_replace(array("\r\n", "\r", "\n", "\t",""), '', $namev);
      // $imgprvs = $shtml->find('ul.cmn-list-product03 img', $i)->src;
      // $imgprv = str_replace(array("https://",""), 'http://', $imgprvs);
      $linkvod = $shtml->find('a.i3NLink', $i)->href;
      $lingvod = urlencode(str_replace(array("/",""), '/', $linkvod));
      $limit[] = [
        // 'name' => $name,
        // 'imgprview' => $imgprvs,
        'linkvod' => $lingvod,
      ];

      // $img = file_get_contents($imgprvs, true, $context);
      // file_put_contents($destdir.substr($imgprvs, strrpos($imgprvs,'/')), $img);
    }
    $fxf[] = [
      'limit_sale' => $limit,
      ];
    echo "<pre>";
    echo json_encode($fxf, JSON_PRETTY_PRINT);
  }
}