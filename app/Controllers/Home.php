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
  public function actress(){
    $client = new httpClient();
    $destdir = 'imgprv/';
    $res = $client->request('GET', 'https://www.r18.com/videos/vod/movies/actress/?page=1');
    $shtml = str_get_html($res->getBody());
    for ($i = 0; $i < 30; $i++) {
      $namev = $shtml->find('div.txt01', $i)->plaintext;
      $name = str_replace(array("\r\n", "\r", "\n", "\t",""), '', $namev);
      $imgprvs = $shtml->find('ul.cmn-list-product03 img', $i)->src;
      $imgprv = str_replace(array("https://",""), 'www.', $imgprvs);
      $aktris[] = [
        'name' => $name,
        'imgprview' => $imgprv,
      ];
      $img=file_get_contents($imgprv);
      file_put_contents($destdir.substr($link, strrpos($link,'/')), $img);
    }
    $fxf[] = [
      'actress' => $aktris,
      ];
    echo "<pre>";
    echo json_encode($fxf, JSON_PRETTY_PRINT);
  }
}