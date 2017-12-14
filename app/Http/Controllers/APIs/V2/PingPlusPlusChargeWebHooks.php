<?php

namespace Zhiyi\Plus\Http\Controllers\APIs\V2;

use Illuminate\Http\Request;
use Zhiyi\Plus\Repository\WalletPingPlusPlus;

class PingPlusPlusChargeWebHooks
{
    public function webhook(Request $request, WalletPingPlusPlus $repository)
    {
        $signature = $request->headers->get('x-pingplusplus-signature');
        $pingPlusPlusPublicCertificate = $repository->get()['secret_key'] ?? null;

        return response()->json([
            'signature' => base64_decode($signature),
            'pub_key' => $pingPlusPlusPublicCertificate,
        ]);

        $signed = openssl_verify($request->getContent(), base64_decode($signature), $pingPlusPlusPublicCertificate, OPENSSL_ALGO_SHA256);

        if (! $signed) {
            return response('加密验证失败', 422);
        }

        $pingPlusPlusCharge = $request->json('data.object');

        return response()->json($pingPlusPlusCharge);
    }
}
