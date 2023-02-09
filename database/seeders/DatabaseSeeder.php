<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Answer;
use App\Models\Question;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use UsersTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->users();
        $this->questions();
        $this->answers();
        // \App\Models\User::factory(10)->create();
        
        
        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
    
    protected function questions()
    {
        $questions = [
        
            [
                'id'=>1,
                'title' => 'Why this program doesnt run ?',
                'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla vel velit lorem. Vivamus ut dictum magna, venenatis blandit sem. Donec porttitor porttitor purus ut placerat. Donec dapibus consequat sem, a consequat magna. Morbi tincidunt quam ut mauris suscipit, sed volutpat enim vehicula. Aenean iaculis ligula at orci euismod porta. Maecenas iaculis fringilla orci, ac feugiat odio lacinia quis. Vivamus ornare vestibulum nisi, vel lacinia metus consectetur id. Integer sit amet felis maximus, feugiat augue a, tempus risus. Quisque nisi erat, gravida nec rhoncus quis, gravida ac metus. Aenean venenatis urna a mauris accumsan efficitur finibus tincidunt ante.

    Morbi eu mollis ligula. Duis iaculis lobortis vestibulum. Vestibulum viverra maximus malesuada. Vivamus diam lorem, sagittis a diam vel, rhoncus condimentum nisi. Nunc non magna vel massa iaculis ultricies. Suspendisse faucibus feugiat interdum. Nulla placerat imperdiet ex lacinia posuere.',
            
                'slug' => 'why-this-program-doesnt-run',
                'status' => 1,
                'created_by' => 1,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],
            [
                'id'=>2,
    
                'title' => 'Failed to sign APK package.',
                'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla vel velit lorem. Vivamus ut dictum magna, venenatis blandit sem. Donec porttitor porttitor purus ut placerat. Donec dapibus consequat sem, a consequat magna. Morbi tincidunt quam ut mauris suscipit, sed volutpat enim vehicula. Aenean iaculis ligula at orci euismod porta. Maecenas iaculis fringilla orci, ac feugiat odio lacinia quis. Vivamus ornare vestibulum nisi, vel lacinia metus consectetur id. Integer sit amet felis maximus, feugiat augue a, tempus risus. Quisque nisi erat, gravida nec rhoncus quis, gravida ac metus. Aenean venenatis urna a mauris accumsan efficitur finibus tincidunt ante. Morbi eu mollis ligula. Duis iaculis lobortis vestibulum. Vestibulum viverra maximus malesuada. Vivamus diam lorem, sagittis a diam vel, rhoncus condimentum nisi. Nunc non magna vel massa iaculis ultricies. Suspendisse faucibus feugiat interdum. Nulla placerat imperdiet ex lacinia posuere.',
            
                'slug' => 'failed-to-sign-apk',
                'status' => 1,
                'created_by' => 2,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],
            [
                'id'=>3,
    
                'title' => 'Image not loading in the template',
                'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla vel velit lorem. Vivamus ut dictum magna, venenatis blandit sem. Donec porttitor porttitor purus ut placerat. Donec dapibus consequat sem, a consequat magna. Morbi tincidunt quam ut mauris suscipit, sed volutpat enim vehicula. Aenean iaculis ligula at orci euismod porta. Maecenas iaculis fringilla orci, ac feugiat odio lacinia quis. Vivamus ornare vestibulum nisi, vel lacinia metus consectetur id. Integer sit amet felis maximus, feugiat augue a, tempus risus. Quisque nisi erat, gravida nec rhoncus quis, gravida ac metus. Aenean venenatis urna a mauris accumsan efficitur finibus tincidunt ante. Morbi eu mollis ligula. Duis iaculis lobortis vestibulum. Vestibulum viverra maximus malesuada. Vivamus diam lorem, sagittis a diam vel, rhoncus condimentum nisi. Nunc non magna vel massa iaculis ultricies. Suspendisse faucibus feugiat interdum. Nulla placerat imperdiet ex lacinia posuere.',
            
                'slug' => 'image-not-loading-in-the-template',
                'status' => 1,
                'created_by' => 1,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ]
        ];
        foreach($questions as $question){
            Question::firstOrCreate(
               [ 'title' => 'Why this program doesnt run ?'],
                $question
            );
        }
      
    }
    
    protected function users()
    {
        
        $users = [
            [
                'id'=>1,
                'name' => 'John Doe',
                'email' => 'johndoe@gmail.com',
                'password' => bcrypt('111111')
            ]
            , [
                'id'=>2,
                'name' => 'Joe Doe',
                'email' => 'joedoe@gmail.com',
                'password' => bcrypt('111111')
            ]];
        
        foreach ($users as $user) {
            
            User::firstOrCreate(
                [
                    'email' => $user['email']
                ],
                $user,
            );
        }
        
    }
    
    protected function answers()
    {
        $answers = [
            [
                'content' => 'First answer',
                'question_id' => 1,
                'answer_id' => null,
                'created_by' => 1,
                'status' => 1,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ]
            , [
                'content' => 'Second answer',
                'question_id' => 1,
                'answer_id' => null,
                'created_by' => 2,
                'status' => 1,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ]
            , [
                'content' => 'Child answer 1',
                'question_id' => 1,
                'answer_id' => null,
                'created_by' => 2,
                'status' => 1,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ]
            , [
                'content' => 'Child answer 2',
                'question_id' => 1,
                'answer_id' => null,
                'created_by' => 2,
                'status' => 1,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ]
        ];
        
        foreach ($answers as $answer) {
            Answer::firstOrCreate(
                [
                    'content' => 'First answer',
                ],
                $answer
            );
        }
        
    }
}
