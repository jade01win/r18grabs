<?php
namespace App\Controllers;
use GuzzleHttp\Client as httpClient;
use Illuminate\Database\Eloquent\Collection;
use Seld\JsonLint\JsonParser;
require_once "simplehtmldom/simplehtmldom.php";

class Home extends BaseController
{
  public function index() {
    return view('welcome_message');
  }
  public function crypto() {
    echo "Data Crypto<br>";
    $id_coin = ['bitcoin',
      'tron'];
    $parser = new JsonParser();
    $client = new httpClient(['base_uri' => 'https://api.coincap.io/v2/']);
    $response = $client->request('GET', 'assets');
    $content = collect(($parser->parse($response->getBody()->getContents())->data ?? []));
    $coin = $content->whereIn('id', $id_coin)->all();
    dd($coin);
  }
  public function stocks() {
    echo "Data Stocks<br>";
    $urlstk = file_get_contents("https://financialmodelingprep.com/api/v3/financial-statement-symbol-lists?apikey=b202b16899d34e9a39be88a25067a031");

    $result = json_decode($urlstk, true);
    echo "<pre>";
    print_r($result);
    echo "</pre>";
  }

  public function forecast() {
    header('Access-Control-Allow-Origin: *');
    header("Content-Type: application/json;charset=utf-8");
    $client = new httpClient();
    $res = $client->request('GET', 'https://www.dailyfx.com/forecasts');
    $shtml = str_get_html($res->getBody());
    for ($i = 0; $i < 7; $i++) {
      $curr = $shtml->find('div.dfx-singleForecastBlock__main h2', $i)->plaintext;
      $judul = $shtml->find('a.dfx-singleForecastBlock__articleTitle', $i)->plaintext;
      $isi = $shtml->find('p.dfx-singleForecastBlock__articleTeaser', $i)->plaintext;
      $tgl = $shtml->find('p.dfx-singleForecastBlock__articleDate', $i)->plaintext;
      $link = $shtml->find('a.dfx-readMoreButton', $i)->getAttribute('href');
      $forecastdfx[] = [
        'curr' => $curr,
        'judul' => $judul,
        'isi' => $isi,
        'tgl' => $tgl,
        'link' => $link,
      ];
    }
    $fxf[] = [
      'fore_ovw' => $forecastdfx,
      ];
    echo "<pre>";
    echo json_encode($fxf, JSON_PRETTY_PRINT);
  }

  public function analyst() {
    header('Access-Control-Allow-Origin: *');
    header("Content-Type: application/json;charset=utf-8");
    $client = new httpClient();
    $analis = $client->request('GET', 'https://www.dailyfx.com/analyst-picks');
    $ahtml = str_get_html($analis->getBody());
    for ($i = 0; $i < 8; $i++) {
      $status = $ahtml->find('h2', $i)->plaintext;
      $tips = $ahtml->find('p.pb-2', $i)->plaintext;
      $judul = $ahtml->find('a.dfx-analystPicksTile__title', $i)->plaintext;
      $linka = $ahtml->find('a.dfx-analystPicksTile__moreButton', $i)->getAttribute('href');
      $result_a[] = [
        'judul_tips' => $judul,
        'market_status' => $status,
        'tips' => $tips,
        'link' => $linka,
      ];
    }
    echo "<pre>";
    echo json_encode($result_a, JSON_PRETTY_PRINT);
  }
  
  public function forex_rates ($pair) {
    header('Access-Control-Allow-Origin: *');
    header("Content-Type: application/json;charset=utf-8");
    $client = new httpClient();
    $urlz = "https://www.dailyfx.com/".$pair;
    $rates = $client->request('GET', $urlz);
    $rhtml = str_get_html($rates->getBody());
    $pivot = $rhtml->find('span.col', 0)->plaintext;
    $s1 = $rhtml->find('span.col', 3)->plaintext;
    $r1 = $rhtml->find('span.col', 4)->plaintext;
    $m_status = $rhtml->find('span.dfx-technicalSentimentCard__signal', 0)->plaintext;
    $result_fr = [
        'pivot' => $pivot,
        's1' => $s1,
        'r1' => $r1,
        'market_status' => $m_status,
      ];
    echo "<pre>";
    echo json_encode($result_fr, JSON_PRETTY_PRINT);
  }
  public function forex_interest () {
  header('Access-Control-Allow-Origin: *');
  header("Content-Type: application/json;charset=utf-8");
    $client = new httpClient();
    $urlfx = "https://www.fxstreet.com/economic-calendar/world-interest-rates";
   $wint = $client->request('GET', $urlfx);
   $rhtml = str_get_html($wint->getBody());
   
   $urlfx = "https://www.fxstreet.com/economic-calendar/world-interest-rates";
   $wint = $client->request('GET', $urlfx);
   $rhtml = str_get_html($wint->getBody());
    for ($i = 0; $i < 17; $i++) {
   $judul = $rhtml->find('h3.fxs_entryHeadline a', $i)->plaintext;
   $nama = "";
   $tgl = "";
   $link = $rhtml->find('h3.fxs_entryHeadline a', $i)->getAttribute('href');
   $isi = "test";
   $jmp = $client->request('GET', $link);
   $jhtml = str_get_html($jmp->getBody());
   $m_react = $jhtml->find('p', 8)->plaintext;
   $q1 =  $jhtml->find('p', 1)->plaintext;
   $q2 =  $jhtml->find('p', 2)->plaintext;
   $q3 =  $jhtml->find('p', 3)->plaintext;
   $q4 =  $jhtml->find('p', 4)->plaintext;
   $fx_int[] = [
     'judul' => $judul,
     'isi_art' => $isi,
     'quote' => [
        'q1' => $q1,
         'q2' => $q2,
          'q3' => $q3,
           'q4' => $q4,
        ],
     'link' => $link,
     'market_reaction' => $m_react,
     ];
    }
    echo "<pre>";
   echo json_encode($fx_int, JSON_PRETTY_PRINT);
  }
}