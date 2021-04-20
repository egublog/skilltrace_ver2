<?php

namespace Tests\Feature\HttpStatus;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;
use App\Models\Language;
use App\Models\UserLanguage;


class SkillTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    function testSkillShow()
    {
        $user = factory(User::class)->create();
        $userLanguage = factory(UserLanguage::class)->create([
            'user_id' => $user->id
        ]);

        $this->actingAs($user)->get(route('skills.show', ['userId' => $user->id, 'skillId' => $userLanguage->language_id]))->assertOk();
    }

    function testSkillCreate()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)->get(route('skills.create'))->assertOk();
    }

    //ここがうまくいかない
    // 選択した言語がlanguage_idに
    function testSkillStore()
    {
        $user = factory(User::class)->create();
        $userLanguage = factory(UserLanguage::class)->create([
            'user_id' => $user->id
        ]);

        $this->actingAs($user)->post(route('skills.store'), ['language_id' => $userLanguage->language_id])->assertStatus(302);
    }

    function testSkillDestroy()
    {
        $user = factory(User::class)->create();
        $userLanguage = factory(UserLanguage::class)->create([
            'user_id' => $user->id
        ]);

        $this->actingAs($user)->delete(route('skills.destroy', ['userLanguageId' => $userLanguage->id]))->assertStatus(302);
    }
}
