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

            if ($user && $user->estatus !== 'activo') {
                return redirect()->route('login')->withErrors(['error' => 'Tu cuenta ha sido desactivada. Contacta al administrador para más información.']);
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
    /**
     * Maneja el login desde la API (App Móvil) usando un token de Google.
     */
    public function handleApiGoogleLogin(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'access_token' => 'required',
        ]);

        try {
            // Usamos stateless para que no intente usar sesiones
            // userFromToken funciona con el access_token obtenido en el móvil
            $socialUser = Socialite::driver('google')->stateless()->userFromToken($request->access_token);
        } catch (\Exception $e) {
            Log::error('Error Google API login: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Token de Google inválido o expirado.'
            ], 401);
        }

        try {
            $user = Usuario::where('google_id', $socialUser->getId())
                ->orWhere('email', $socialUser->getEmail())
                ->first();

            if ($user) {
                if (!$user->google_id) {
                    $user->update(['google_id' => $socialUser->getId()]);
                }
                if ($socialUser->getAvatar() && !$user->foto_perfil) {
                    $user->update(['foto_perfil' => $socialUser->getAvatar()]);
                }
            } else {
                $user = Usuario::create([
                    'nombre' => $socialUser->getName() ?? $socialUser->getNickname() ?? 'Usuario Google',
                    'email' => $socialUser->getEmail(),
                    'google_id' => $socialUser->getId(),
                    'foto_perfil' => $socialUser->getAvatar(),
                    'password' => null,
                    'estatus' => 'activo',
                ]);
                $user->asignarRol('inquilino');
            }

            if ($user->estatus !== 'activo') {
                return response()->json(['message' => 'Cuenta desactivada.'], 403);
            }

            // Generar token de Sanctum
            $token = $user->createToken('mobile-auth')->plainTextToken;

            return response()->json([
                'success' => true,
                'token' => $token,
                'usuario' => [
                    'id' => $user->id,
                    'nombre' => $user->nombre,
                    'email' => $user->email,
                    'rol' => $user->roles()->first()?->nombre ?? 'inquilino',
                    'roles' => $user->roles()->pluck('nombre'),
                    'public_id' => (string)$user->id,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar el usuario: ' . $e->getMessage()
            ], 500);
        }
    }
}
