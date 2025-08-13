<?php

namespace App\Livewire\User\Auth;

use App\Models\User;
use App\Services\MagicLinkService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Component;

class UserMagicLinkPage extends Component
{
    public $token;
    public $email;
    public $isValid = false;
    public $isExpired = false;
    public $user = null;
    public $autoLogin = false;
    public $existingOrdersCount = 0;
    public $linkedOrdersCount = 0;

    public function mount($token = null)
    {
        $this->token = $token;
        
        if ($this->token) {
            $this->validateToken();
        }
    }

    protected function validateToken()
    {
        try {
            $magicLinkService = app(MagicLinkService::class);
            $magicLink = $magicLinkService->validateToken($this->token);

            if (!$magicLink) {
                $this->isExpired = true;
                return;
            }

            $this->email = $magicLink['email'];
            $this->isValid = true;

            // Check if user exists
            $this->user = User::where('email', $this->email)->first();

            // Get count of existing orders for this email
            $this->existingOrdersCount = $magicLinkService->getExistingOrdersCount($this->email);

            // Auto-login if user exists
            if ($this->user) {
                $this->autoLogin();
            }

        } catch (\Exception $e) {
            $this->isExpired = true;
        }
    }

    protected function autoLogin()
    {
        if ($this->user) {
            auth()->login($this->user);
            
            // Mark the magic link as used
            $magicLinkService = app(MagicLinkService::class);
            $magicLinkService->markAsUsed($this->token);

            // Redirect to dashboard
            return $this->redirect(route('user.dashboard'));
        }
    }

    public function createAccount()
    {
        if (!$this->isValid || !$this->email) {
            return;
        }

        // Generate a random password
        $password = Str::random(12);

        try {
            // Create the user
            $user = User::create([
                'name' => 'User', // Default name, user can update later
                'email' => $this->email,
                'mobile' => null, // Can be updated later
                'password' => Hash::make($password),
                'status' => true,
            ]);

            // Assign default user role if exists
            $defaultRole = \Spatie\Permission\Models\Role::where('name', 'user')->first();
            if ($defaultRole) {
                $user->assignRole($defaultRole);
            }

            // Link existing orders to this user account
            $magicLinkService = app(MagicLinkService::class);
            $this->linkedOrdersCount = $magicLinkService->linkOrdersToUser($this->email, $user->id);

            // Login the user
            auth()->login($user);

            // Mark the magic link as used
            $magicLinkService->markAsUsed($this->token);

            // Store success message in session
            if ($this->linkedOrdersCount > 0) {
                session()->flash('success', "Account created successfully! {$this->linkedOrdersCount} repair request(s) have been linked to your account.");
            } else {
                session()->flash('success', 'Account created successfully! Welcome to your dashboard.');
            }

            // Redirect to dashboard
            return $this->redirect(route('user.dashboard'));

        } catch (\Exception $e) {
            session()->flash('error', 'Failed to create account. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.user.auth.user-magic-link-page')
            ->layout('components.layouts.user_auth');
    }
}
