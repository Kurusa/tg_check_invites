<?php

use TelegramBot\Api\Client;
use TelegramBot\Api\Types\Update;

require_once(__DIR__ . '/bootstrap.php');
$client = new Client(env('TELEGRAM_BOT_API_KEY'));
$client->on(function (Update $update) {
    $bot = new \TelegramBot\Api\BotApi(env('TELEGRAM_BOT_API_KEY'));


    $user = \App\Models\StopUser::where('chat_id', $update->getMessage()->getFrom()->getId())->first();
    if (!$user) {
        \App\Models\StopUser::create([
            'chat_id' => $update->getMessage()->getFrom()->getId(),
            'user_name' => $update->getMessage()->getFrom()->getUsername()
        ]);
        $user = \App\Models\StopUser::where('chat_id', $update->getMessage()->getFrom()->getId())->first();
    }
    if ($update->getMessage()->getNewChatMembers()) {
        foreach ($update->getMessage()->getNewChatMembers() as $member) {

            $user->increment('invited_count');
            $user->save();

            if ($user->invited_count == 5) {
                break;
            }
        }
    }

    if ($user->invited_count < 5 && !$update->getMessage()->getNewChatMembers()) {
            $bot->sendMessage($update->getMessage()->getChat()->getId(), $update->getMessage()->getFrom()->getUsername(). ', تنبيه!!
يمكنك الكتابة بعد أضافة 5 أصدقاء للمجموعة لمرة واحدة فقط وبعد ذلك تستطيع الكتابة بحريتك دون الحاجة 
     لأضافة المزيد.
     
Для того, что бы писать Сообщения , добавьте единоразово 5 человек в группу');
        $bot->deleteMessage($update->getMessage()->getChat()->getId(), $update->getMessage()->getMessageId());
    }

}, function(Update $update) {
    return $update->getMessage()!== null;
});
$client->run();