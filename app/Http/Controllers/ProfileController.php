<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Mostrar el formulario de edición de perfil
     */
    public function edit()
    {
        $usuario = Auth::user();
        return view('profile.edit', compact('usuario'));
    }

    /**
     * Actualizar la información básica del perfil
     */
    public function update(Request $request)
    {
        $usuario = Auth::user();

        $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'codigo_estudiante' => ['nullable', 'string', 'max:50'],
        ]);

        $usuario->update([
            'nombre' => $request->nombre,
            'telefono' => $request->telefono,
            'codigo_estudiante' => $request->codigo_estudiante,
        ]);

        return redirect()->route('profile.edit')
            ->with('success', 'Perfil actualizado correctamente');
    }

    /**
     * Actualizar la foto de perfil
     */
    public function updateAvatar(Request $request)
    {
        $usuario = Auth::user();

        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'], // Max 2MB
        ]);

        // Eliminar avatar anterior si existe
        if ($usuario->avatar && Storage::disk('public')->exists($usuario->avatar)) {
            Storage::disk('public')->delete($usuario->avatar);
        }

        // Guardar nueva imagen
        $path = $request->file('avatar')->store('avatars', 'public');

        $usuario->update([
            'avatar' => $path,
        ]);

        return redirect()->route('profile.edit')
            ->with('success', 'Foto de perfil actualizada correctamente');
    }

    /**
     * Eliminar la foto de perfil
     */
    public function deleteAvatar()
    {
        $usuario = Auth::user();

        if ($usuario->avatar && Storage::disk('public')->exists($usuario->avatar)) {
            Storage::disk('public')->delete($usuario->avatar);
        }

        $usuario->update([
            'avatar' => null,
        ]);

        return redirect()->route('profile.edit')
            ->with('success', 'Foto de perfil eliminada correctamente');
    }

    /**
     * Actualizar la contraseña
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'current_password.required' => 'La contraseña actual es requerida',
            'current_password.current_password' => 'La contraseña actual es incorrecta',
            'password.required' => 'La nueva contraseña es requerida',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
        ]);

        $usuario = Auth::user();

        $usuario->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('profile.edit')
            ->with('success', 'Contraseña actualizada correctamente');
    }
}