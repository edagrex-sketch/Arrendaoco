<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Account;
use Stripe\AccountLink;

class StripeConnectController extends Controller
{
    public function onboard()
    {
        $user = auth()->user();

        Stripe::setApiKey(config('services.stripe.secret'));

        // 1. Create a custom or express account if the user doesn't have one
        if (!$user->stripe_account_id) {
            $account = Account::create([
                'type' => 'express',
                'country' => 'MX', // Assuming users are in Mexico for this marketplace
                'email' => $user->email,
                'capabilities' => [
                    'card_payments' => ['requested' => true],
                    'transfers' => ['requested' => true],
                ],
                'business_type' => 'individual',
            ]);

            $user->stripe_account_id = $account->id;
            $user->save();
        } else {
            $account = Account::retrieve($user->stripe_account_id);
        }

        // 2. Create an Account Link for onboarding
        $accountLink = AccountLink::create([
            'account' => $user->stripe_account_id,
            'refresh_url' => route('stripe.connect.refresh'),
            'return_url' => route('stripe.connect.return'),
            'type' => 'account_onboarding',
        ]);

        return redirect($accountLink->url);
    }

    public function handleReturn(Request $request)
    {
        $user = auth()->user();
        Stripe::setApiKey(config('services.stripe.secret'));

        $account = Account::retrieve($user->stripe_account_id);

        if ($account->details_submitted) {
            $user->stripe_onboarding_completed = true;
            $user->save();
            
            if ($request->platform === 'mobile') {
                return view('auth.stripe_mobile_return');
            }

            return redirect()->route('perfil.index')->with('success', '¡Cuenta bancaria configurada exitosamente! Ahora puedes realizar cobros automáticos.');
        }

        if ($request->platform === 'mobile') {
            return redirect()->away('arrendaoco://verify-stripe?error=1');
        }

        return redirect()->route('perfil.index')->with('error', 'El proceso de configuración no fue completado. Por favor, intenta de nuevo cuando estés listo.');
    }

    public function handleRefresh()
    {
        // Simply redirect them back to the onboarding function to try again
        return redirect()->route('stripe.connect.onboard');
    }
}
