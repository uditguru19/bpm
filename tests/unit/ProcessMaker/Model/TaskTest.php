<?php

namespace Tests\Unit;

use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use ProcessMaker\Model\Task;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Tests create email server
     */
    public function testCreateEmailServer()
    {
        $faker = Faker::create();

        $type = $faker->randomElement([Task::TYPE_NORMAL, Task::TYPE_ADHOC, Task::TYPE_SUB_PROCESS, Task::TYPE_HIDDEN, Task::TYPE_GATEWAY, Task::TYPE_WEB_ENTRY_EVENT, Task::TYPE_END_MESSAGE_EVENT, Task::TYPE_START_MESSAGE_EVENT, Task::TYPE_INTERMEDIATE_THROW_MESSAGE_EVENT, Task::TYPE_INTERMEDIATE_CATCH_MESSAGE_EVENT, Task::TYPE_SCRIPT_TASK, Task::TYPE_START_TIMER_EVENT, Task::TYPE_INTERMEDIATE_CATCH_TIMER_EVENT, Task::TYPE_END_EMAIL_EVENT, Task::TYPE_INTERMEDIATE_THROW_EMAIL_EVENT, Task::TYPE_SERVICE_TASK]);
        $assignType = $faker->randomElement([Task::ASSIGN_TYPE_BALANCED, Task::ASSIGN_TYPE_MANUAL, Task::ASSIGN_TYPE_EVALUATE, Task::ASSIGN_TYPE_REPORT_TO, Task::ASSIGN_TYPE_SELF_SERVICE, Task::ASSIGN_TYPE_STATIC_MI, Task::ASSIGN_TYPE_CANCEL_MI, Task::ASSIGN_TYPE_MULTIPLE_INSTANCE, Task::ASSIGN_TYPE_MULTIPLE_INSTANCE_VALUE_BASED]);
        $routingType = $faker->randomElement([Task::ROUTE_TYPE_NORMAL, Task::ROUTE_TYPE_FAST, Task::ROUTE_TYPE_AUTOMATIC]);

        $task = factory(Task::class)->create([
            'type' => $type,
            'assign_type' => $assignType,
            'routing_type' => $routingType,
            'timing_control_configuration' => [
                'duration' => 10
            ],
            'self_service_timeout_configuration' => [
                'self_service_timeout' => 10
            ],
        ]);

        $this->assertGreaterThan(0, $task->id);
        $this->assertEquals($type, $task->type);
        $this->assertEquals($assignType, $task->assign_type);
        $this->assertEquals($routingType, $task->routing_type);

    }

}
