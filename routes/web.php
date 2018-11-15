<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/noticias', function () {
  error_reporting(E_ALL);
ini_set('display_errors', 1);
// 1. get the content Id (here: an Integer) and sanitize it properly
$slug = filter_input(INPUT_GET, 'slug');
// 2. get the content from a flat file (or API, or Database, or ...)
$data = json_decode(file_get_contents('http://hindu.prototiposwebs.tk/wp-json/wp/v2/articulo?slug=' . $slug));
var_dump($data);
die;
$data = $data[0];
function normaliza ($cadena){
  $originales = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞ
ßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ“”';
  $modificadas = 'aaaaaaaceeeeiiiidnoooooouuuuy
bsaaaaaaaceeeeiiiidnoooooouuuyybyRr--';
  $cadena = utf8_decode($cadena);
  $cadena = strtr($cadena, utf8_decode($originales), $modificadas);
  $cadena = strtolower($cadena);
  return utf8_encode($cadena);
}
$title = $data->title->rendered;
$title = normaliza($title);

var_dump($data->featured_media);
die;
// Imagen de portada
$image = json_decode(file_get_contents('https://admin.culturachaco.com.ar/wp-json/wp/v2/media/' . $data->featured_media));
$image = $image->source_url;
$image = $data->acf->imagen_de_portada;
// 3. return the page
return makePage($data, $title, $image);
function makePage($data, $title, $image) {
  // 1. get the page
  $pageUrl = "http://prueba.hinduchaco.com.ar/noticia/" . $data->slug;
  // 2. generate the HTML with open graph tags
  $html  = '<!doctype html>'.PHP_EOL;
  $html .= '<html>'.PHP_EOL;
  $html .= '<head>'.PHP_EOL;
  $html .= '<title>'. $title .'</title>';
  $html .= '<meta name="author" content="'.$title.'"/>'.PHP_EOL;
  $html .= '<meta property="og:title" content="'.$title.'"/>'.PHP_EOL;
  $html .= '<meta property="og:image" content="'.$image.'"/>'.PHP_EOL;
  $html .= '<meta http-equiv="refresh" content="0;url='.$pageUrl.'">'.PHP_EOL;
  $html .= '</head>'.PHP_EOL;
  $html .= '<body></body>'.PHP_EOL;
  $html .= '</html>';
  // 3. return the page
  echo $html;
}
});


Route::resource('notebook','NotebookController');

Route::get('/home', 'HomeController@index');


Route::post('register','UserController@register');
Route::post('login','UserController@login');
// Route::get('logout','UserController@logout');
