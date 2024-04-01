<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;
    protected $max_login_attempts;
    protected $recaptcha_secret;

    protected function setUp(): void
    {
        parent::setUp();
        $this->max_login_attempts = config('session.login_attempts');
        $this->recaptcha_secret = config('services.recaptcha.secret_key');
    }

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_user_can_authenticate_after_n_minus_one_times_failure(): void
    {

        // $this->markTestSkipped('Error jika tidak ada captcha.'); // Fixed di Captcha Validation (attempt+1 used instead of attempt)
        $user = User::factory()->create();

        for ($i = 0; $i < $this->max_login_attempts - 1; $i++) {
            $response = $this->post('/login', [
                'email' => $user->email,
                'password' => 'wrong-password',
            ]);
            $this->assertGuest();
        }

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_user_can_authenticate_after_n_minus_two_times_failure(): void
    {
        $user = User::factory()->create();
        for ($i = 0; $i < $this->max_login_attempts - 2; $i++) {
            $response = $this->post('/login', [
                'email' => $user->email,
                'password' => 'wrong-password',
            ]);
            $this->assertGuest();
        }

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_user_can_not_authenticate_after_n_times_failure(): void
    {
        $user = User::factory()->create();
        for ($i = 0; $i < $this->max_login_attempts; $i++) {
            $this->post('/login', [
                'email' => $user->email,
                'password' => 'wrong-password',
            ]);
        }
        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertGuest();
    }

    public function test_user_login_with_solved_captcha_after_n_times_failure(): void
    {
        $user = User::factory()->create();
        for ($i = 0; $i < $this->max_login_attempts; $i++) {
            $this->post('/login', [
                'email' => $user->email,
                'password' => 'wrong-password',
            ]);
        }
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
            'recaptcha_token' => $this->recaptcha_secret,
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }
}
