<?php

use App\FrontMsg;
use App\User;
use Illuminate\Database\Seeder;

class FrontMsgSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Add a message
        $msg = new FrontMsg();
        $msg->message = 'Initialize';
        $msg->lastUpdatedBy = 1364926;
        $msg->save();

        // Add a message
        $msg = new FrontMsg();
        $msg->message = 'Initialize';
        $msg->lastUpdatedBy = 1364926;
        $msg->save();

        // Add me! (The first user)
        $user = new User();
        $user->id = 1364926;
        $user->fname = 'Ian';
        $user->lname = 'Cowan';
        $user->is_ztl_staff = 1;
        $user->email = 'email@email.com';
        $user->save();
    }
}
