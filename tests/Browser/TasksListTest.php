<?php

namespace Tests\Browser;

use ProcessMaker\Model\Process;
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
            $process = Process::find(1);
            $browser->loginAs(User::find(1))
                ->visit('/process/' . $process->uid . '/tasks')
                ->assertSee('Tasks');
        });
    }
}
