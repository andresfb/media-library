<?php

namespace App\Services;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class AvatarGeneratorService
{
    use WithFaker;

    private Collection $providers;

    public function __construct()
    {
        $this->setUpFaker();
        $this->providers = collect([
            'dice-bear' => [
                'url' => 'https://avatars.dicebear.com/api/%s/%s.png',
                'options' => collect(['male', 'female', 'human', 'bottts', 'avataaars', 'micah']),
                'can_name' => true,
            ],
            'robo-hash' => [
                'url' => 'https://robohash.org/%s%s.png',
                'options' => collect([]),
                'can_name' => true,
            ],
            'random-user' => [
                'url' => 'https://xsgames.co/randomusers/avatar.php?g=%s%s',
                'options' => collect(['male', 'female', 'pixel']),
                'can_name' => false,
            ],
            'lorem-space' => [
                'url' => 'https://api.lorem.space/image/%s%s?w=150&h=150',
                'options' => collect(['album', 'face', 'fashion', 'pizza', 'burger', 'car']),
                'can_name' => false,
            ],
            'joeschmoe' => [
                'url' => 'https://joeschmoe.io/api/v1/%s%s/random',
                'options' => collect(['male', 'female']),
                'can_name' => false,
            ],
            'multi-avatar' => [
                'url' => 'https://api.multiavatar.com/%s%s.png',
                'options' => collect([]),
                'can_name' => true,
            ]
        ]);
    }

    /**
     * getAvatar Method.
     *
     * @return array
     */
    public function getAvatar(): array
    {
        $provider = $this->providers->random();
        $name = sprintf("%s %s", $this->faker->firstName(), $this->faker->lastName());
        $option = $provider['options']->isNotEmpty()
            ? $provider['options']->random()
            : '';

        $avatar = sprintf(
            $provider['url'],
            $option,
            $provider['can_name'] ? Str::slug($name) : '',
        );

        return [
            'name' => $name,
            'image' => $avatar,
        ];
    }
}
