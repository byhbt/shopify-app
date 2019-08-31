<?php
namespace App\Http\Controllers\Auth;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Socialite;
use Auth;
use App\User;

class LoginShopifyController extends Controller
{
    /**
     * Redirect to provider.
     *
     * @param Request $request
     * @return void
     * @throws \Illuminate\Validation\ValidationException
     */
    public function redirectToProvider(Request $request)
    {
        $this->validate($request, [
            'domain' => 'string|required',
        ]);

        $config = new \SocialiteProviders\Manager\Config(
            env('SHOPIFY_KEY'),
            env('SHOPIFY_SECRET'),
            env('SHOPIFY_REDIRECT'),
            [
                'subdomain' => $request->get('domain'),
            ]
        );

        return Socialite::with('shopify')
            ->setConfig($config)
            ->scopes(['read_products', 'write_products'])
            ->redirect();
    }

    /**
     * Handle shopify callback.
     */
    public function handleProviderCallback()
    {
        $shopifyUser = Socialite::driver('shopify')->user();
        if (!$shopifyUser) {
            return false;
        }

        dd($shopifyUser);

        $user = User::firstOrCreate([
            'name' => $shopifyUser->nickname,
            'email' => $shopifyUser->email,
            'password' => '',
        ]);

        $this->registerWebhooks($shopifyUser->name, $shopifyUser->token);

        Auth::login($user, true);

        return redirect('/home');
    }

    /**
     * Register web hooks
     *
     * @param $shop
     * @param $accessToken
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function registerWebhooks($shop, $accessToken)
    {
        $webHookRoutes = [
            'webhook-uninstall-app',
            'webhook-products-create',
            'webhook-products-update',
            'webhook-products-delete',
            'webhook-shop-update',
        ];

        foreach ($webHookRoutes as $webHookRoute) {
            $url = config('app.webhook_url') . route($webHookRoute, [], false);

            $registerParams = [
                "topic" => "app/uninstalled",
                "address" => $url,
                "format" => "json",
            ];

            $this->register($shop, $accessToken, $registerParams);
        }
    }

    /**
     * @param string $shop
     * @param string $accessToken
     * @param array $registerParams
     * @return \Illuminate\Http\RedirectResponse|void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function register($shop, $accessToken, $registerParams)
    {
        $endpoint = sprintf('https://%s/admin/api/%s/webhooks.json', $shop, '2019-04');

        $client = new Client();
        try {
            $client->request('POST', $endpoint, [
                'headers' => [
                    'X-Shopify-Access-Token' => $accessToken,
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
                'form_params' => [
                    'webhook' => $registerParams,
                ],
            ]);

            return;
        } catch (ClientException $ex) {
            // 422 status code: webhook had already registered, ignore exception
            if ($ex->getCode() != 422) {
                Auth::guard()->logout();

                return redirect()->route('identity-sign-in');
            }
        }
    }
}
