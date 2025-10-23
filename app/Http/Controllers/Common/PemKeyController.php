<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\PasswordLocker;
use App\Models\User;
use App\Models\UserPublicKey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use phpseclib3\Crypt\RSA;

class PemKeyController extends Controller
{
    public function generateAndDownload()
    {
        $user = Auth::user();
        $privateKey = RSA::createKey(2048);
        $publicKey  = $privateKey->getPublicKey();

        $privatePem = $privateKey->toString('PKCS8');
        $publicPem  = $publicKey->toString('PKCS8');

        UserPublicKey::updateOrCreate(
            ['user_id' => $user->id],
            ['public_pem' => $publicPem]
        );

        $filename = 'private-key-' . $user->username . '-' . date('Y-m-d_H-i-s') . '.pem';
        return response($privatePem, 200, [
            'Content-Type' => 'application/x-pem-file',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control' => 'no-store'
        ]);
    }


    public function getChallenge(Request $request)
    {
        $nonce = base64_encode(random_bytes(32));
        $challengeId = Str::uuid()->toString();

        cache()->put("pk:challenge:{$challengeId}", $nonce, now()->addMinutes(5));

        return response()->json([
            'challenge_id' => $challengeId,
            'nonce' => $nonce
        ]);
    }

    public function verifySignature(Request $request)
    {
        $request->validate([
            'challenge_id' => 'required|string',
            'signature'    => 'required|string',
            'password_locker_id' => 'required|integer'
        ]);
        $user = Auth::user();
        $challengeId = $request->input('challenge_id');
        $nonceBase64 = cache()->pull("pk:challenge:{$challengeId}");

        if (!$nonceBase64) {
            return response()->json(['status' => 'error', 'message' => 'invalid/expired challenge'], 400);
        }

        $nonce = base64_decode($nonceBase64);

        $userKey = UserPublicKey::where('user_id', $user->id)->first();
        if (!$userKey) {
            return response()->json(['status' => 'error', 'message' => 'no public key registered'], 400);
        }

        $publicPem = $userKey->public_pem;
        $signature = base64_decode($request->input('signature'));

        $ok = openssl_verify($nonce, $signature, $publicPem, OPENSSL_ALGO_SHA256);

        if ($ok === 1) {
            $encryptedPassword = PasswordLocker::where('user_id', $user->id)->where('id', $request->password_locker_id)->value('password');

            if (!empty($encryptedPassword)) {

                PasswordLocker::where('id', $request->password_locker_id)->update([
                    'last_used_at' => now()
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'ok',
                    'key' => Crypt::decryptString($encryptedPassword)
                ]);
            } else {
                return response()->json(['status' => 'error', 'message' => 'Unautorized access!'], 401);
            }
        } elseif ($ok === 0) {
            return response()->json(['status' => 'error', 'message' => 'signature invalid'], 401);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'verification error',
                'error' => openssl_error_string()
            ], 500);
        }
    }
}
