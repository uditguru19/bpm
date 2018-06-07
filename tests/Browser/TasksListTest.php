<?php

namespace Tests\Browser;

use ProcessMaker\Model\Process;
use ProcessMaker\Model\Task;
use ProcessMaker\Model\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;

class TasksListTest extends DuskTestCase
{
    /**
     * @throws \Throwable
     */
    public function testExample()
    {
        $this->browse(function (Browser $browser) {
            $user = User::find(1);
            $process = factory(Process::class)->create([
                'creator_user_id' => $user->id
            ]);

            factory(Task::class, 10)->create([
                'process_id' => $process->id
            ]);

            $browser->loginAs($user)
                ->visit('/process/' . $process->uid . '/tasks')
                ->assertSee('Tasks');
        });
    }
}
