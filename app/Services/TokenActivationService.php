<?php

namespace App\Services;

use App\Models\Activation;
use Illuminate\Database\Eloquent\Model;

class TokenActivationService
{
    private function getToken(): string
    {
        return hash_hmac('sha256', str_random(40), config('app.key'));
    }

    public function createActivation(Model $model): string
    {
        $activation = $this->getActivation($model);

        if (!$activation) {
            return $this->createToken($model);
        }

        return $this->regenerateToken($model);
    }

    private function regenerateToken(Model $model): string
    {
        $token = $this->getToken();

        Activation::where('entity_id', '=', $model->id)
                    ->where('entity_type', '=', ($model instanceof \App\Models\User) ? 'user' : 'domain')
                    ->update(['token' => $token]);

        return $token;
    }

    private function createToken(Model $model): string
    {
        $token = $this->getToken();

        Activation::create([
            'entity_id' => $model->id,
            'entity_type' => 'user',
            'token' => $token,
        ]);

        return $token;
    }

    public function getActivation(Model $model): ?Activation
    {
        return Activation::where('entity_id', '=', $model->id)
            ->typeUser()
            ->first();
    }

    public function getActivationByToken(string $token): Activation
    {
        return Activation::where('token', $token)->first();
    }

    public function deleteActivation(string $token): bool
    {
        return Activation::where('token', $token)->delete();
    }

}