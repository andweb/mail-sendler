<?php

use Faker\Factory;

require __DIR__.'/../vendor/autoload.php';

$config = require __DIR__ . '/../config/config.php';

/**
 * @param array $data
 * @param string $status
 */
function returnJSON(array $data, $status = 'OK') {
    header('Content-Type: application/json');

    echo json_encode([
        'status' => $status,
        'data' => $data
    ]);

    /** Nginx sends a response while php works */
    fastcgi_finish_request();
}

/**
 * Initialization
 */
$faker = Factory::create();

$client = new GearmanClient();
$client->addServer($config['queue']['server'], $config['queue']['port']);

/**
 * Email params
 */
$mail = [
    'to'            => $_POST['to'] ?: $faker->safeEmail,
    'subject'       => $_POST['subject'] ?: $faker->text(20),
    'body'          => $_POST['message'] ?: $faker->text(100),
    'subscribers'   => 1
];

/**
 * Urgent message
 */
if (isset($_GET['high'])) {
    returnJSON($mail);

    // High priority
    $client->doHighBackground ('sendmail', json_encode($mail));
}
/**
 * Start newsletter
 */
else {
    unset($mail['to']);
    $mail['subscribers'] = mt_rand(10, 20);

    returnJSON($mail);

    for ($i = 0; $i < $mail['subscribers']; $i++) {

        $mail['to'] = $faker->safeEmail;

        // Low priority
        $client->doLowBackground ('sendmail', json_encode($mail));
    }
}