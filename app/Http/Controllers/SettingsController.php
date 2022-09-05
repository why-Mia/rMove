<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Classes\ApiRequest;
use App\Models\RobloxAccount;

class SettingsController extends Controller
{
    public function update(\App\Http\Requests\UpdateSettingsRequest $request){
        auth()->user()->update($request->only(keys:['name','email']));
        if($request->input(key:'password')){
            auth()->user()->update([
                'password' => bcrypt($request->input(key:'password'))
            ]);
        }
        return redirect()->route(route:'settings')->with('message', 'Settings successfully updated');
    }

    public function check_username(\App\Http\Requests\CheckUsernameRequest $request){
        $url = 'https://users.roblox.com/v1/usernames/users';
        $data = array('usernames' => [$request['roblox_username']]);
        $users_api_request = new ApiRequest();
        $user_data = $users_api_request->post($url, $data);
        if($user_data !== false){
            $user_data = $user_data->data[0];
            if(property_exists($user_data,'id')){
                $verification_code = $this->create_code();
                auth()->user()->update([
                    'roblox_verification_code' => $verification_code
                ]);
                return json_encode(['user_exists' => true, 'error_in_response' => false, 'id' => $user_data->id, 'verification_code' => $verification_code]);
            }
            else{
                return json_encode(['user_exists' => false, 'error_in_response' => false]);
            }
        }
        else{
            return  json_encode(['error_in_response' => true]);
        }
    }

    public function refresh_verification_code(\App\Http\Requests\RefreshCodeRequest $request){
        $verification_code = $this->create_code();
        auth()->user()->update([
            'roblox_verification_code' => $verification_code
        ]);
        return json_encode(['verification_code' => $verification_code]);
    }

    public function verify_account(\App\Http\Requests\VerifyAccountRequest $request){
        $url = 'https://users.roblox.com/v1/usernames/users';
        $data = array('usernames' => [$request['roblox_username']]);
        $users_api_request = new ApiRequest();
        $user_data = $users_api_request->post($url, $data);
        if($user_data !== false){
            $user_data = $user_data->data[0];
            if(property_exists($user_data, 'id') && property_exists($user_data, 'name') ){
                $description_api_request = new ApiRequest();
                $user_description_data = $description_api_request->get('https://users.roblox.com/v1/users/'.$user_data->id);
                $avatar_api_request = new ApiRequest();
                $avatar_data = $avatar_api_request->get('https://thumbnails.roblox.com/v1/users/avatar?userIds='.$user_data->id.'&size=250x250&format=Png&isCircular=false');
                if($avatar_data !== false && property_exists($avatar_data->data[0], 'imageUrl')){
                    $avatar_data = $avatar_data->data[0];
                    if($user_description_data !== false && property_exists($user_description_data, 'description')){
                        if(str_contains($user_description_data->description, auth()->user()->roblox_verification_code)){ //If user description contains verification code
                            $user = auth()->user();
                            //If the user already has a linked account set as the primary account,  ̶c̶h̶a̶n̶g̶e̶ ̶t̶h̶e̶ ̶o̶t̶h̶e̶r̶ ̶a̶c̶c̶o̶u̶n̶t̶'̶s̶ ̶i̶s̶_̶p̶r̶i̶m̶a̶r̶y̶_̶a̶c̶c̶o̶u̶n̶t̶ ̶t̶o̶ ̶f̶a̶l̶s̶e̶  delete the account
                            if($user->robloxAccounts()->where('is_primary_account',true)->exists()){ 
                                /*$user->robloxAccounts()->where('is_primary_account',true)->update([
                                    'is_primary_account' => false
                                ]);*/
                                $user->robloxAccounts()->where('is_primary_account',true)->delete();
                            }
                            //If the entered user ID is already linked to the current user, update that account
                            if($user->robloxAccounts()->where('roblox_id',$user_data->id)->exists()){
                                $roblox_account = $user->robloxAccounts()->where('roblox_id',$user_data->id)->first();
                            }
                            //Otherwise, make a new account to link
                            else{
                                $roblox_account = new RobloxAccount;
                            }
                            $roblox_account->is_primary_account = true;
                            $roblox_account->roblox_id = $user_data->id;
                            $roblox_account->username = $user_data->name;
                            $roblox_account->displayname = $user_data->displayName;
                            $roblox_account->avatar_image_url = $avatar_data->imageUrl;
                            $user->robloxAccounts()->save($roblox_account);
                            \Session::flash('message','Roblox account successfully linked');
                            return json_encode(['user_exists' => true, 'error_in_response' => false, 'description_string_found' => true]);
                        }
                        else{
                            //Description string not found
                            return json_encode(['user_exists' => true, 'error_in_response' => false, 'description_string_found' => false]);
                        }
                    }
                }
            }
            //If user data was found but no other return path was reached, there was an error in one of the API responses
            return  json_encode(['user_exists' => true, 'error_in_response' => true]); 
        }
        //User doesn't exist
        return json_encode(['user_exists' => false, 'error_in_response' => false]);
    }

    public function unlink_account(\App\Http\Requests\UnlinkAccountRequest $request){
        if(auth()->user()->robloxAccounts()->where('roblox_id',$request['roblox_id'])->exists()){
            auth()->user()->robloxAccounts()->where('roblox_id',$request['roblox_id'])->delete();
            return redirect()->route(route:'settings')->with('message', 'Account successfully unlinked');
        }
        else{
            return redirect()->route(route:'settings')->with('error', 'no');
        }        
    }

    private function create_code(){
        $word_list = array("abandon","ability","able","about","above","absent","absorb","abstract","absurd","access","accident","account","accuse","achieve","acoustic","acquire","across","act","action","actor","actual","adapt","add","address","adjust","admit","advance","advice","aerobic","afford","afraid","again","agent","agree","ahead","air","airport","aisle","alarm","album","alert","alien","all","allow","almost","alone","alpha","already","also","alter","always","amateur","amazing","among","amount","amused","anchor","ancient","anger","angle","angry","animal","ankle","announce","annual","another","answer","antenna","antique","any","apart","apology","appear","apple","approve","april","arch","arctic","area","arena","argue","arm","armor","around","arrange","arrive","arrow","art","artefact","artist","artwork","ask","aspect","asset","assist","assume","athlete","atom","attend","attitude","attract","auction","audit","august","aunt","author","auto","autumn","average","avocado","avoid","awake","aware","away","awesome","awful","awkward","bachelor","bacon","badge","bag","balance","balcony","ball","bamboo","banana","banner","barely","bargain","barrel","base","basic","basket","battle","beach","bean","bear","beauty","because","become","beef","before","begin","behave","behind","believe","below","belt","bench","benefit","best","betray","better","between","beyond","bicycle","bike","bind","bird","bitter","blame","blanket","bleak","bless","blind","blossom","blue","blur","board","boat","bonus","book","boost","boring","borrow","boss","bounce","box","boy","bracket","brain","brand","brave","bread","breeze","brick","bridge","brief","bright","bring","brisk","broccoli","broken","bronze","broom","brother","brush","bubble","buddy","budget","buffalo","build","bulb","bulk","bundle","bunker","burden","burger","burst","bus","business","busy","butter","buyer","buzz","cabbage","cabin","cable","cactus","cake","call","calm","camera","camp","can","candy","cannon","canoe","canvas","canyon","capable","capital","captain","car","carbon","card","cargo","carpet","carry","cart","case","cash","castle","casual","cat","catalog","catch","category","cattle","caught","cause","caution","cave","ceiling","celery","cement","census","century","cereal","certain","chair","chalk","champion","change","chaos","chapter","charge","chase","chat","cheap","check","chef","cherry","chicken","chief","chimney","choice","choose","chuckle","chunk","churn","cinnamon","circle","citizen","city","civil","claim","clap","clarify","clay","clean","clerk","clever","click","client","cliff","climb","clip","clock","clog","close","cloth","cloud","club","cluster","clutch","coach","coast","coconut","code","coffee","coin","collect","color","column","combine","come","comfort","comic","common","company","concert","conduct","confirm","connect","consider","control","convince","cook","cool","copper","copy","coral","core","corn","correct","cost","couch","country","course","cover","coyote","cradle","craft","cram","crane","crash","crater","crawl","crazy","credit","creek","crew","cricket","crisp","crop","crossed","crouch","crowd","crucial","cruise","crumble","crunch","crying","crystal","cube","cup","cupboard","curious","current","curtain","cushion","custom","cycle","dad","damage","dance","danger","daring","dash","dawn","day","deal","debate","debris","decade","december","decide","decline","decorate","decrease","deer","defense","define","defy","degree","delay","deliver","demand","demise","denial","dentist","deny","depart","depend","deposit","depth","deputy","derive","describe","desert","design","desk","despair","detail","detect","develop","device","devote","diagram","dial","diamond","diary","dice","diesel","differ","digital","dignity","dilemma","dinner","dinosaur","direct","disagree","discover","dish","dismiss","display","distance","divert","divide","dizzy","document","dog","dolphin","domain","donate","donkey","door","double","dove","draft","dragon","drama","drastic","draw","dream","dress","drift","drill","drip","drive","drop","drum","dry","duck","dune","during","dust","dutch","duty","dwarf","dynamic","eager","eagle","early","earn","earth","easily","east","easy","echo","ecology","economy","edit","educate","effort","eight","either","elbow","elder","electric","elegant","element","elephant","elevator","elite","embark","embody","embrace","emerge","emotion","employ","empower","empty","enable","enact","end","endless","endorse","enemy","energy","enforce","engage","engine","enhance","enjoy","enough","enrich","enroll","ensure","enter","entire","entry","envelope","episode","equal","equip","era","erase","erode","erosion","error","erupt","escape","essay","essence","estate","eternal","evidence","evoke","evolve","exact","example","excess","exchange","excite","exclude","excuse","exercise","exhaust","exhibit","exile","exist","exit","exotic","expand","expect","expire","explain","express","extend","extra","eyelash","eyebrow","fabric","face","faculty","fade","faint","faith","fall","false","fame","family","famous","fan","fancy","fantasy","farm","fashion","fatigue","fault","favorite","feature","february","federal","fee","feed","feel","fence","festival","fetch","fever","few","fiber","fiction","field","figure","file","film","filter","final","find","fine","finish","fire","firm","first","fiscal","fish","fit","fitness","fix","flame","flash","flat","flavor","flee","flight","flip","float","flock","floor","flower","fly","foam","focus","fog","foil","fold","follow","food","foot","force","forest","forget","fork","fortune","forum","forward","fossil","found","fox","fragile","frame","frequent","fresh","fringe","frog","front","frost","frown","frozen","fruit","fuel","fun","funny","furnace","fury","future","gadget","gain","galaxy","gallery","game","gap","garage","garbage","garden","garlic","garment","gasp","gate","gather","gauge","gaze","general","genius","genre","gentle","genuine","gesture","ghost","giant","gift","giggle","giraffe","give","glad","glance","glare","glass","glide","glimpse","globe","gloom","glory","glove","glow","glue","goat","goddess","gold","good","goose","gorilla","gossip","govern","gown","grace","grain","grant","grape","grass","gravity","great","green","grid","grief","grit","grocery","group","grow","grunt","guard","guess","guide","guilt","guitar","gym","habit","half","hammer","hamster","hand","happy","harbor","harsh","harvest","hat","haven","hawk","hazard","head","health","heavy","hedgehog","height","hello","helmet","help","hero","hidden","hill","hint","hire","history","hobby","hockey","hold","holiday","hollow","home","hope","horn","horse","host","hotel","hour","hover","hub","huge","humble","humor","hundred","hungry","hurdle","hurry","hybrid","ice","icon","idea","identity","idle","ignore","image","imitate","immense","immune","impact","impose","improve","impulse","inch","include","income","increase","index","indicate","indoor","industry","inform","inherit","initial","injury","inner","innocent","input","inquiry","insane","insect","inspire","install","intact","interest","invest","invite","involve","iron","island","isolate","issue","item","jacket","jaguar","jar","jazz","jealous","jeans","jelly","jewel","job","join","joke","journey","joy","judge","juice","jump","jungle","junior","just","kangaroo","keen","keep","ketchup","key","kick","kidney","kind","kingdom","kit","kitchen","kite","kitten","kiwi","knee","knock","know","lab","label","ladder","lake","lamp","language","laptop","large","later","latin","laugh","laundry","lava","lawn","layer","lazy","leader","leaf","learn","leave","lecture","left","leg","legal","legend","leisure","lemon","lend","length","lens","leopard","lesson","letter","level","liar","library","license","life","lift","light","like","limit","link","lion","liquid","list","little","live","lizard","load","loan","lobster","local","lock","logic","lonely","long","loop","loud","lounge","loyal","lucky","luggage","lumber","lunar","lunch","luxury","lyrics","machine","mad","magic","magnet","mail","main","major","make","mammal","manage","mandate","mango","mansion","manual","maple","marble","march","margin","marine","market","mask","mass","match","material","math","matrix","matter","max","maximum","maze","meadow","mean","measure","mechanic","medal","media","melody","melt","member","memory","mention","menu","mercy","merge","merit","merry","mesh","message","metal","method","middle","midnight","million","mimic","mind","minimum","minute","miracle","mirror","misery","miss","mistake","mix","mixer","mixture","mobile","mod","model","modify","mom","moment","momentum","monitor","monster","month","moon","moral","more","morning","mother","motion","motor","mountain","mouse","move","movie","much","muffin","mule","multiply","muscle","museum","music","must","mutual","myself","mystery","myth","naive","name","napkin","narrow","nation","nature","near","need",
        "negative","neglect","neither","nervous","nest","net","network","neutral","never","next","nice","night","noble","noise","nominee","north","notable","noted","nothing","notice","novel","numbered","nurse","oak","oblige","obscure","observe","obtain","obvious","occur","ocean","october","off","offer","office","often","okay","old","olympic","omit","once","one","onion","only","opened","opera","opinion","oppose","option","orbit","orchard","order","ordinary","organize","original","ostrich","other","outdoor","outer","output","outside","oval","over","oxygen","oyster","ozone","paddle","page","pair","palace","palm","panda","panel","panic","paper","parade","parked","parrot","party","pass","patch","path","patient","patrol","pattern","pause","paved","payment","peace","pear","peasant","pelican","penalty","pencil","people","pepper","perfect","permit","phone","photo","phrase","physical","piano","picnic","picture","piece","pigeon","pilot","pioneer","pitch","pizza","place","planet","plastic","plate","play","please","pledge","plug","plunge","poem","poet","point","polar","pond","pony","pool","popular","portion","position","possible","post","potato","pottery","powder","practice","praise","predict","prefer","prepare","present","pretty","prevent","price","primary","print","priority","prize","problem","process","produce","profit","program","project","promote","proof","property","prosper","protect","proud","provide","pull","pulse","pumpkin","pupil","puppy","purchase","purity","purpose","purse","push","puzzle","pyramid","quality","quantum","quarter","question","quick","quit","quiz","quote","rabbit","raccoon","racing","radar","radio","rail","rain","raise","rally","ramp","ranch","random","range","rapid","rare","rate","rather","raven","raw","ready","real","reason","rebel","rebuild","recall","receive","recipe","record","recycle","reduce","reflect","reform","refuse","region","regret","regular","reject","relax","release","relief","rely","remain","remember","remind","remove","render","renew","rent","reopen","repair","repeat","replace","report","require","rescue","resemble","resist","resource","response","result","retire","retreat","return","reunion","reveal","review","reward","rhythm","ribbon","rice","rich","ride","ridge","right","rigidity","ring","ripple","risk","rival","river","road","roast","robot","robust","rocket","romance","roof","rookie","room","rose","rotate","rough","round","route","royal","rug","rule","run","runway","rural","sadden","saddle","sadness","safe","sail","salad","salmon","salon","salute","sample","sand","satisfy","save","scale","scan","scare","scatter","scene","scheme","science","scissors","scorpion","scout","scrap","screen","script","scrub","sea","search","season","seat","second","secret","section","security","seek","segment","select","sell","seminar","senior","sense","sentence","series","service","session","settle","setup","seven","shadow","shallow","share","shed","shell","shield","shift","shine","shiver","shock","shoe","shop","short","shoulder","shove","shrimp","shrug","shuffle","shy","sick","side","siege","sight","sign","silent","silk","silly","silver","similar","simple","since","sing","siren","situate","six","skate","sketch","ski","skill","slab","sleep","slice","slide","slight","slogan","slow","slush","small","smart","smile","smooth","snack","snap","snow","soap","soccer","social","sock","soda","sofa","solar","solid","solution","solve","song","soon","sorry","sort","sound","soup","source","south","space","spare","spatial","spawn","speak","special","speed","spell","spend","sphere","spice","spider","spin","spirit","split","spoil","sponsor","sport","spot","spring","spy","square","squeeze","squirrel","stable","stadium","staff","stage","stairs","stamp","stand","start","state","stay","steak","steel","stem","step","stereo","stick","still","sting","stock","story","stove","strategy","street","strike","strong","struggle","stuff","stumble","style","subject","submit","subway","success","such","sudden","suffer","sugar","suggest","suit","summer","sun","sunny","sunset","super","supply","supreme","sure","surface","surge","surprise","surround","survey","suspect","sustain","swap","swarm","sweet","swift","swim","swing","switch","sword","symbol","system","table","tackle","tag","tail","talent","talk","tape","target","task","taxi","teach","team","tell","ten","tenant","tennis","tent","term","test","text","thank","theme","theory","thing","thought","three","thrive","throw","thumb","thunder","ticket","tide","tiger","tilt","timber","time","tiny","tip","tired","tissue","title","toast","today","toe","together","toilet","token","tomato","tomorrow","tone","tonight","tool","tooth","top","topic","topple","torch","tornado","tortoise","toss","total","tourist","toward","tower","town","toy","track","tragic","train","transfer","trash","travel","tray","treat","tree","trend","trial","tribe","trick","trim","trip","trophy","trouble","truck","true","truly","trumpet","trust","truth","try","tube","tuition","tumble","tuna","tunnel","turkey","turn","turtle","twelve","twenty","twice","twin","twist","two","type","typical","umbrella","unable","unaware","uncle","uncover","undo","unfair","unfold","unhappy","uniform","unique","unit","universe","unknown","unlock","until","unusual","unveil","update","upgrade","uphold","upon","upper","upset","urban","urge","usage","use","used","useful","useless","usual","utility","vacant","vacuum","vague","valid","valley","valve","vanish","various","vast","vault","vehicle","velvet","vendor","venture","venue","verb","verify","version","very","vessel","veteran","viable","vibrant","vicious","victory","video","view","village","vintage","violin","virtual","visit","visual","vital","vivid","vocal","voice","void","volcano","volume","vote","voyage","wage","wagon","wait","walk","wall","walnut","wanted","warm","warrior","wash","wasp","waste","water","wave","way","wealth","wear","weasel","weather","web","weekend","weird","welcome","west","whale","what","wheat","wheel","when","where","whisper","wide","width","wild","will","win","window","wine","wing","winner","winter","wisdom","wise","wish","witness","wolf","wonder","wood","wool","word","work","world","worry","worth","wrap","wreck","wrestle","wrist","write","wrong","yard","year","yellow","zebra","zero","zone","zoo");
        $max = count($word_list)-1;
        $verification_code = $word_list[random_int(0,$max)].' '.$word_list[random_int(0,$max)].' '.$word_list[random_int(0,$max)].' '.$word_list[random_int(0,$max)].' '.$word_list[random_int(0,$max)];
        return $verification_code;
    }
}
