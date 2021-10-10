<?php

namespace Database\Factories\User;

use App\Models\User\User;
use App\Models\User\Rank;
use Illuminate\Database\Eloquent\Factories\Factory;
use Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // First things first, check if user ranks exist...
        if(!Rank::count()) {
            // Create ranks if not already present.
            // A light-weight rank system is used here since the site is intended
            // only for individuals or small groups/granular permissions are not
            // necessary.
            $adminRank = Rank::create([
                'name' => 'Admin',
                'description' => 'The site admin. Has the ability to view/edit any data on the site.',
                'sort' => 2
            ]);

            Rank::create([
                'name' => 'Editor',
                'description' => 'A member of the site with write permissions.',
                'sort' => 1
            ]);

            Rank::create([
                'name' => 'Member',
                'description' => 'A regular member of the site.',
                'sort' => 0
            ]);
        }

        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'rank_id' => Rank::orderBy('sort', 'ASC')->first(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the user is an editor.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function editor()
    {
        return $this->state(function (array $attributes) {
            return [
                'rank_id' => Rank::orderBy('sort', 'DESC')->skip(1)->first(),
            ];
        });
    }

    /**
     * Indicate that the user is an admin.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function admin()
    {
        return $this->state(function (array $attributes) {
            return [
                'rank_id' => Rank::orderBy('sort', 'DESC')->first(),
            ];
        });
    }
}
