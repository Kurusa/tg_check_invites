<?php
require_once(__DIR__ . '/bootstrap.php');

$update = \json_decode(file_get_contents('php://input'), TRUE);
$tg = new \App\Services\SevenTelegramApi();
$parse = new \App\Services\TelegramParser($update);
$user = \App\Models\StopUserSeven::firstOrCreate([
    'chat_id' => $parse::getUserId(),
    'user_name' => $parse::getUserName()
]);

if ($update['message']['new_chat_members']) {
    foreach ($update['message']['new_chat_members'] as $member) {
        $user->increment('invited_count');
        if ($user->invited_count == 5) {
            break;
        }
    }
}

if ($user->invited_count < 7) {
    $tg->sendMessage($parse::getUserName() . '
يمكنك الكتابة بعد أضافة 7 أصدقاء للمجموعة لمرة واحدة فقط وبعد ذلك تستطيع الكتابة بحريتك دون الحاجة 
     لأضافة المزيد.

Внимание! ' . $parse::getUserName() . '
Для того, что бы писать в группе, добавьте единоразово 7 человек и публикуйте  далее без ограничений.

Attention! ' . $parse::getUserName() . '
In order to write in a group, add 7 people one-time and publish further without restrictions.', $parse::getGroupId());
    $tg->deleteMessage($parse::getGroupId(), $parse::getMsgId());
}