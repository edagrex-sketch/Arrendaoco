<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    protected $providers = ['google', 'facebook'];

    /**
     * Redirige al usuario al proveedor de autenticación.
     */
    public function redirectToProvider($provider)
    {
        if (!in_array($provider, $this->providers)) {
            return redirect()->route('login')->withErrors(['error' => 'Proveedor no soportado.']);
        }

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Maneja el callback del proveedor.
     */
    public function handleProviderCallback($provider)
    {
        if (!in_array($provider, $this->providers)) {
            return redirect()->route('login')->withErrors(['error' => 'Proveedor no soportado.']);
        }

        try {
            $socialUser = Socialite::driver($provider)->stateless()->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors([
                'error' => 'Error al autenticar con ' . ucfirst($provider) . ': ' . $e->getMessage()
            ]);
        }

        try {
            // Buscar usuario existente por social_id o email
            $user = Usuario::where($provider . '_id', $socialUser->getId())
                ->orWhere('email', $socialUser->getEmail())
                ->first();

            if ($user) {
                // Vincular social_id si no lo tiene
                if (!$user->{$provider . '_id'}) {
                    $user->update([$provider . '_id' => $socialUser->getId()]);
                }
                // Actualizar avatar
                if ($socialUser->getAvatar()) {
                    $user->update(['foto_perfil' => $socialUser->getAvatar()]);
                }
            } else {
                // Crear usuario nuevo
                $user = Usuario::create([
                    'nombre' => $socialUser->getName(),
                    'email' => $socialUser->getEmail(),
                    $provider . '_id' => $socialUser->getId(),
                    'foto_perfil' => $socialUser->getAvatar(),
                    'password' => null,
                    'estatus' => 'activo',
                ]);
                $user->asignarRol('inquilino');
            }

            // Iniciar sesión
            Auth::login($user, true); // true = remember me

            session()->regenerate();
            session()->flash('login_success', true);

            // Redirigir directamente con URL absoluta de ngrok
            return redirect()->to(config('app.url') . '/inicio');

        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors([
                'error' => 'Error al procesar tu cuenta: ' . $e->getMessage()
            ]);
        }
    }
}
