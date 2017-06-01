<?php
/**
 * message file
 *
 * @author Oleksandr Muzychenko <avionwd@gmail.com>
 */

require_once __DIR__ . '/../../vendor/fzaninotto/faker/src/autoload.php';

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Mvc\Model\Migration;

/**
 * Class message
 */
class MessageMigration_1 extends Migration
{
    public function up()
    {
        $definitions = [
            'columns' => [
                new Column(
                    'id',
                    [
                        'type' => Column::TYPE_INTEGER,
                        "autoIncrement" => true,
                        'primary' => true,
                        'first' => true,
                    ]),
                new Column(
                    'name',
                    [
                        'type' => Column::TYPE_VARCHAR,
                        'size' => 64,
                        'notNull' => true,
                        'after' => 'id'
                    ]
                ),
                new Column(
                    'phone',
                    [
                        'type' => Column::TYPE_VARCHAR,
                        'size' => 24,
                        'after' => 'name'
                    ]
                ),
                new Column(
                    'email',
                    [
                        'type' => Column::TYPE_VARCHAR,
                        'size' => 128,
                        'after' => 'phone'
                    ]
                ),
                new Column(
                    'message',
                    [
                        'type' => Column::TYPE_VARCHAR,
                        'size' => 200,
                        'after' => 'email'
                    ]
                ),
                new Column(
                    'date_create',
                    [
                        'type' => Column::TYPE_TIMESTAMP,
                        'after' => 'message',
                        'default' => 'now'
                    ]
                ),
                new Column(
                    'date_update',
                    [
                        'type' => Column::TYPE_TIMESTAMP,
                        'after' => 'date_create',
                        'default' => 'now'
                    ]
                ),
            ],
            'indexes' => [
                new Index('message_filter', ['name', 'phone', 'email'])
            ]
        ];

        self::$_connection->createTable('message', 'public', $definitions);


        $faker = Faker\Factory::create();
        for ($i=0; $i < 10; $i++) {
            self::$_connection->insert('public.message',
                [
                    $faker->name,
                    $faker->e164PhoneNumber,
                    $faker->email,
                    $faker->text(200),
                    'now',
                    'now'
                ],
                [
                    'name',
                    'phone',
                    'email',
                    'message',
                    'date_create',
                    'date_update'
                ]);
        }
    }

    public function down()
    {
        self::$_connection->dropTable('message', 'public');
    }
}