<?
mb_internal_encoding("UTF-8");
$token = ''; //токен принимающей группы
$usertoken = ''; //токен юзера от имени которго будетет отправлять
$data = json_decode(file_get_contents('php://input'), true);


$chats = array('C#'=>80,'тест'=>114); //'название беседы'=>id беседы
//$info = file_put_contents('info.txt',json_encode($chats));
//$chats = json_decode(file_get_contents('info.txt'),true);
if($data['type'] == 'confirmation') 
{
  echo '703e1625';
}
else 
if ($data['type'] == 'message_new')
{
  $object = $data['object'];
  $user = $object['peer_id'];
  $response = '';
  $message = $object['text'];
  if((isStart($message, 'ch') or isStart($message, 'Ch')) and $user == ) // ch 2 #
  { //search($text,$token)

    $files = scandir('/home/host1751252/host1751252.hostland.pro/htdocs/www/audio');//ссылка на папку с аудио
   
    $mas = explode(' ',$message);
    $id = 2000000000+$mas[2];
    $aud = intval($mas[1]);
    $str = './audio/'.$files[$aud];
    $doc = Doc2($str,$usertoken);
    sendMessagePhoto('', $id, $usertoken, $doc);
    sendMessagecurl($files[$aud].'  '.$doc, $user, $token);
  }
  elseif(isStart($message, 'id') and $user == ) // ch 2 #
  { 
    $files = scandir('/home/host1751252/host1751252.hostland.pro/htdocs/www/audio');//ссылка на папку с аудио
    
    $mas = explode(' ',$message);
    $id = $mas[2];
    $aud = intval($mas[1]);
    $str = './audio/'.$files[$aud];
    $doc = Doc2($str,$usertoken);
    sendMessagePhoto('', $id, $usertoken, $doc);
    sendMessagecurl($files[$aud].' '.$doc, $user, $token);
  }
  elseif(isStart($message, 'help') or isStart($message, 'Help') and $user == )
  {
    $files = scandir('/home/host1751252/host1751252.hostland.pro/htdocs/www/audio');//ссылка на папку с аудио
    print_r($files);
    //$i=-1;
    $resp = '';
    /*for($i=0;$i>=130;$i++)
    {
        $resp=$resp.$i.') '.$files[$i]."\n";
    }
    //print_r($resp);*/
    $i = -1;
    foreach ($files as $key) 
    {
      $i=$i+1;
      $resp = $resp.$i.') '.$key."\n";
    }
    file_put_contents('car.txt',$resp);
    sendMessagecurl($resp, $user, $token);
    sendMessage('C#=>80'.'тест=>114', $user, $token);
  }
  else 
  {
  
    if($user != )
    {
      die("ok");
    }
    
    $mas = explode(' ',$message);
    if(array_key_exists($mas[0],$chats))
    {
    
      $id = 2000000000+$chats[$mas[0]];
      $files = scandir('/home/host1751252/host1751252.hostland.pro/htdocs/www/audio');//ссылка на папку с аудио
      $aud = intval($mas[1]);
      $str = './audio/'.$files[$aud];
      $doc = Doc2($str,$usertoken);
      sendMessagePhoto('', $id, $usertoken, $doc);
      sendMessagecurl($files[$aud].'  '.$doc, $user, $token);
      
    }
    else
    {
        
      sendMessagecurl('help', $user, $token);
    }
  }
  echo 'ok';
}

function Doc2($x,$token)
{
$req = array(
'v' => '5.68',
'type' => 'audio_message',
'access_token' => $token
);
$get_params = http_build_query($req);
$url = json_decode(file_get_contents('https://api.vk.com/method/docs.getMessagesUploadServer?'. $get_params),true);
//print_r($url);
$ch = curl_init();curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$parameters = array(
'file' => new CURLFile($x)
);
curl_setopt($ch, CURLOPT_URL, $url['response']['upload_url']);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$curl_result = curl_exec($ch);
curl_close($ch);
$res = json_decode($curl_result);
//print_r($res);
$req = [
'file' => $res->file,
'v' => '5.74',
'access_token' => $token
];
$get_params = http_build_query($req);
$res = json_decode(file_get_contents('https://api.vk.com/method/docs.save?'. $get_params),true);
//print_r($res);
return 'doc'.$res['response'][0]['owner_id'].'_'.$res['response'][0]['id'];

}
function sendMessageAt($text, $id, $token, $doc)
{
  $request_params = [
    'message' => $text,
    'peer_id' => $id,
    'attachment' => $doc,
    'access_token' => $token,
    'v' => '5.80'
  ];
  $resp = file_get_contents('https://api.vk.com/method/messages.send?'.http_build_query($request_params));
  
}
function groups_isMember($group_id, $user_id, $token) //Подписчик ли группы
  {
    $resp = 'https://api.vk.com/method/groups.isMember?user_id='.$user_id.'&group_id='.$group_id.'&v=5.68&access_token='.$token;
    $resp = file_get_contents($resp);
    $result = json_decode($resp,true);
    return $result;
    //https://vk.com/dev/groups.isMember
  }
function user_get($user_ids, $fields, $token) //получение информации о пользователе
  {
    $resp = 'https://api.vk.com/method/users.get?user_ids='.$user_ids.'&fields='.$fields.'&v=5.67&access_token='.$token;
    $resp = file_get_contents($resp);
    $result = json_decode($resp,true);
    return $result;
    //https://vk.com/dev/users.get
  }

function isStart($str, $substr)//Проверка начинается ли строка с указаной строчки, $str - где надо искать, $substr - начало строки
{
    $result = strpos($str, $substr);
    if ($result === 0) {
      return true;
    } else {
      return false; 
    }
} //messages.search
function search($text,$token)
{
     $request_params = [
    'q' => $text,
    'access_token' => $token,
    'v' => '5.80'
  ];
  $resp = file_get_contents('https://api.vk.com/method/messages.search?'.http_build_query($request_params));
  $resp = json_decode($resp, true);
  return $resp;
}
function sendMessage($text, $id, $token)
{
     $request_params = [
    'message' => $text,
    'peer_id' => $id,
    'access_token' => $token,
    'v' => '5.80'
  ];
  $resp = file_get_contents('https://api.vk.com/method/messages.send?'.http_build_query($request_params));
} //{"type":"message_new","object":{"id":105,"date":1522505193,"out":0,"user_id":158351094,"read_state":0,"title":"","body":"https:\/\/vk.com\/wall-164420205_2"},"group_id":164420205}messages.getConversationMembers
function getConversationMembers($id, $token) 
{
     $request_params = [
     'peer_id' => $id,
     'access_token' => $token,
     'group_id' => 162819323,
     'v' => '5.80'
  ];
  $resp = file_get_contents('https://api.vk.com/method/messages.getConversationMembers?'.http_build_query($request_params));
  $resp = json_decode($resp,true);
  return $resp;
}
function sendMessageForward($text, $id, $idmsg, $token)
{
  $request_params = [
    'message' => $text,
    'peer_id' => $id,
    'forward_messages' => $idmsg,
    'access_token' => $token,
    'v' => '5.80'
  ];
  $resp = file_get_contents('https://api.vk.com/method/messages.send?'.http_build_query($request_params));
}
function sendMessagecurl($text, $id, $token)
{
  $request_params = [
    'message' => $text,
    'peer_id' => $id,
      'access_token' => $token,
    'v' => '5.80'
  ];
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.vk.com/method/messages.send?');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($request_params));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$curl_result = curl_exec($ch);
curl_close($ch);
return $curl_result;
}
function sendMessagePhoto($text, $id, $token, $doc)
{
 $request_params = [
    'message' => $text,
    'peer_id' => $id,
    //'keyboard' => '',
    'attachment' => $doc,
    'access_token' => $token,
    'v' => '5.80'
  ];
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.vk.com/method/messages.send?');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($request_params));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$curl_result = curl_exec($ch);
curl_close($ch);
return $curl_result;
}
?>