<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Account;
use Stripe\AccountLink;

class StripeConnectController extends Controller
{
    public function getOnboardingLink(Request $request)
    {
        $user = $request->user();

        Stripe::setApiKey(config('services.stripe.secret'));

        // 1. Create or Retrieve the Stripe account
        if (!$user->stripe_account_id) {
            $account = Account::create([
                'type' => 'express',
                'country' => 'MX',
                'email' => $user->email,
                'capabilities' => [
                    'card_payments' => ['requested' => true],
                    'transfers' => ['requested' => true],
                ],
                'business_type' => 'individual',
            ]);

            $user->stripe_account_id = $account->id;
            $user->save();
        }

        // 2. Create the Account Link
        // Mobile app doesn't have local routes, so we use web routes for return/refresh
        // Stripe will redirect the mobile browser to these web pages when done
        $accountLink = AccountLink::create([
            'account' => $user->stripe_account_id,
            'refresh_url' => route('stripe.connect.refresh', ['platform' => 'mobile']),
            'return_url' => route('stripe.connect.return', ['platform' => 'mobile']),
            'type' => 'account_onboarding',
        ]);

        return response()->json([
            'url' => $accountLink->url,
            'completed' => (bool)$user->stripe_onboarding_completed
        ]);
    }

    public function checkStatus(Request $request)
    {
        $user = $request->user();
        if (!$user->stripe_account_id) {
            return response()->json(['completed' => false]);
        }

        Stripe::setApiKey(config('services.stripe.secret'));
        $account = Account::retrieve($user->stripe_account_id);

        if ($account->details_submitted && !$user->stripe_onboarding_completed) {
            $user->stripe_onboarding_completed = true;
            $user->save();
        }

        return response()->json([
            'completed' => (bool)$user->stripe_onboarding_completed
        ]);
    }
}
