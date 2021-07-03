<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

//        //
        VerifyEmail::createUrlUsing(function( $notifiable ){
            $id = $notifiable->getKey();
            $hash = sha1( $notifiable->getEmailForVerification() );

            $url = URL::temporarySignedRoute('verification.verify',now()->addMinutes(1),[
                'id'=>$id,
                'hash'=>$hash
            ]);

            return $url;

        });
        VerifyEmail::toMailUsing(function ($notifiable, $url) {
            return (new MailMessage())
                ->subject('Verify Email Address')
                ->line('Click the button below to verify your email address.')
                ->action('Verify Email Address', $url);
        });
        ResetPassword::createUrlUsing(function( $user, string $token ){
            // このURLがメールにリンクとして挿入されます。tokenを使用して、APIへリクエストします
            return 'http://localhost:9000/reset-password/' . $token;
        });

    }
}
