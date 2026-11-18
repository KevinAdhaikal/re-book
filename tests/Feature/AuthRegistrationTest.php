<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class AuthRegistrationTest extends TestCase
{
    public function test_guest_sees_masuk_and_daftar_buttons_on_navbar()
    {
        $response = $this->get(route('home'));
        $response->assertStatus(200);
        $response->assertSee(route('login'));
        $response->assertSee(route('register'));
        $response->assertSee('Masuk');
        $response->assertSee('Daftar');
    }

    public function test_login_page_renders_link_to_register()
    {
        $response = $this->get(route('login'));
        $response->assertStatus(200);
        $response->assertSee(route('register'));
        $response->assertSee('Belum punya akun?');
        $response->assertSee('Daftar akun baru');
    }

    public function test_register_page_renders_form_and_link_to_login()
    {
        $response = $this->get(route('register'));
        $response->assertStatus(200);
        $response->assertSee(route('login'));
        $response->assertSee('Buat Akun Member Baru');
        $response->assertSee('Sudah punya akun?');
        $response->assertSee('Masuk di sini');
    }

    public function test_member_registration_validation_and_success_flow()
    {
        $email = 'new_member_' . uniqid() . '@rebook.com';

        // 1. Password confirmation mismatch
        $responseMismatch = $this->post(route('register'), [
            'name' => 'Member Baru Test',
            'email' => $email,
            'password' => 'password123',
            'password_confirmation' => 'mismatch123',
        ]);
        $responseMismatch->assertSessionHasErrors(['password']);
        $this->assertGuest();

        // 2. Successful registration
        $responseSuccess = $this->post(route('register'), [
            'name' => 'Member Baru Test',
            'email' => $email,
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $responseSuccess->assertRedirect(route('member.profile'));
        $this->assertAuthenticated();

        $createdUser = User::where('email', $email)->first();
        $this->assertNotNull($createdUser);
        $this->assertEquals('member', $createdUser->role);
        $this->assertEquals('Aktif', $createdUser->status);

        // Clean up
        $createdUser->delete();
    }
}
