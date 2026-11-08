<?php
$base = dirname(__DIR__, 2);
$loader = require ($argv[1] ?? $base . '/vendor/autoload.php');
$loader->setPsr4('App\\', [$base . '/app']);
$app = new Illuminate\Foundation\Application($base);
$app->instance('config', new Illuminate\Config\Repository([
 'database' => ['default'=>'sqlite', 'connections'=>['sqlite'=>['driver'=>'sqlite','database'=>':memory:','prefix'=>'']]],
 'morocco'=>['enabled'=>true], 'app'=>['locale'=>'en']
]));
Illuminate\Support\Facades\Facade::setFacadeApplication($app);
$app->register(Illuminate\Database\DatabaseServiceProvider::class);
$app->register(Illuminate\Filesystem\FilesystemServiceProvider::class);
$app->register(Illuminate\Translation\TranslationServiceProvider::class);
$app->register(Illuminate\Validation\ValidationServiceProvider::class);
$db=$app['db']->connection();
foreach ([
 'languages'=>'id integer primary key, code text',
 'projects'=>'id integer primary key, title text',
 'property_countries'=>'id integer primary key autoincrement, created_at text, updated_at text',
 'property_country_contents'=>'country_id integer, language_id integer, name text, slug text, updated_at text',
 'property_states'=>'id integer primary key autoincrement, country_id integer, created_at text, updated_at text',
 'property_state_contents'=>'state_id integer, language_id integer, name text, slug text, updated_at text',
 'property_cities'=>'id integer primary key autoincrement, country_id integer, state_id integer, image text, status integer, serial_number integer, featured integer, created_at text, updated_at text',
 'property_city_contents'=>'city_id integer, language_id integer, name text, slug text, created_at text, updated_at text'
] as $table=>$columns) $db->statement("CREATE TABLE $table ($columns)");
$db->table('languages')->insert([['id'=>1,'code'=>'fr'],['id'=>2,'code'=>'ar']]);
$db->table('projects')->insert(['id'=>1,'title'=>'Existing listing']);
function check($condition,$message) { if(!$condition) throw new RuntimeException($message); }
function runImport($app) {
 $cmd=new App\Console\Commands\ConfigureMoroccoMarket(); $cmd->setLaravel($app);
 $output=new Symfony\Component\Console\Output\BufferedOutput();
 check($cmd->run(new Symfony\Component\Console\Input\ArrayInput([]),$output)===0,'Import failed');
 echo $output->fetch();
}
runImport($app);
check($db->table('property_cities')->count()===1503,'Commune count');
check($db->table('property_states')->count()===12,'Region count');
check($db->table('property_city_contents')->count()===3006,'Bilingual labels');
$db->table('property_city_contents')->where('city_id',1)->where('language_id',2)->delete();
$db->table('languages')->insert(['id'=>3,'code'=>'en']);
runImport($app);
check($db->table('property_cities')->count()===1503,'Import duplicated communes');
check($db->table('property_city_contents')->count()===4509,'Repair or added language failed');
check($db->table('projects')->value('title')==='Existing listing','Existing listing changed');
$city=$db->table('property_cities')->first();
$request=Illuminate\Http\Request::create('/', 'POST', ['country_id'=>$city->country_id,'state_id'=>$city->state_id,'city_id'=>$city->id]);
$rules=App\Support\MoroccoMarket::propertyRules($request,[]);
check($app['validator']->make($request->all(),$rules)->passes(),'Valid Moroccan city rejected');
$request->merge(['state_id'=>9999]);
check($app['validator']->make($request->all(),App\Support\MoroccoMarket::propertyRules($request,[]))->fails(),'Mismatched region accepted');
$request->merge(['country_id'=>9999,'state_id'=>$city->state_id]);
check($app['validator']->make($request->all(),App\Support\MoroccoMarket::propertyRules($request,[]))->fails(),'Foreign country accepted');
echo "PASS: bilingual import, repeat import, missing translation repair, new language, existing listing preservation, valid and invalid locations.\n";
