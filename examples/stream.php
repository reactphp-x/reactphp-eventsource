<?php

require __DIR__ . '/../vendor/autoload.php';

if (!isset($argv[1]) || isset($argv[2])) {
    exit('Usage error: stream.php <uri>' . PHP_EOL);
}

$es = new Clue\React\EventSource\EventSource('POST',$argv[1], $data = [
    'model' => 'moonshot-v1-8k',
    'messages' => [
        [
            'role' => 'system',
            'content' => '你是个乐于助人的助手'
        ],
        [
            'role' => 'user',
            'content' => 'hi'
        ]
    ],
    'stream' => true
],  [
    'Authorization' => 'Bearer sk-CvL4w9WYyHeVntZK1sFDn5k6meOKhnbsrWWcCtMK8SLMRDVL',
    'Content-Type' => 'application/json',
    'Cache-Control' => 'no-cache'
]);

$es->on('message', function (Clue\React\EventSource\MessageEvent $message) {
    // $json = json_decode($message->data);
    var_dump($message->data);
});

$es->on('open', function () {
    echo 'open' . PHP_EOL;
});

// 非事件流的数据
$es->on('_data', function ($chunk) {
    var_dump($chunk);
});

$es->on('close', function () {
    echo 'close' . PHP_EOL;
});

$es->on('error', function (Exception $e) use ($es) {
    // 返回的不是事件流
    if ($e instanceof \UnexpectedValueException) {
        // $es->close();
        echo 'Permanent error: ' . $e->getMessage() . PHP_EOL;
    } else {
        // 事件流正常关闭了
        $es->close();
        echo 'Temporary error: ' . $e->getMessage() . PHP_EOL;
    }
});
