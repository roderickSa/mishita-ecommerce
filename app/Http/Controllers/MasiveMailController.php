<?php

namespace App\Http\Controllers;

use App\Models\SendMasiveMail;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MasiveMailController extends Controller
{

    const PREFIX_KEY_MASIVE_EMAILS = 'MASIVE_EMAILS';

    public function masivemail(Request $request): JsonResponse
    {
        $quantity = $request->quantity ?? 10;

        $data = $this->generateEmails($quantity);

        SendMasiveMail::insert($data);

        return response()->json(["message" => 'emails will be preceseed', 'data' => $data], Response::HTTP_OK);
    }

    /* public function masivemail(): JsonResponse
    {
        $redis = Redis::connection();

        $key = self::PREFIX_KEY_MASIVE_EMAILS;

        $data = $this->generateEmails();

        $redis->set(
            $key,
            json_encode($data)
        );
        return response()->json(["message" => 'emails will be preceseed', 'data' => $data], Response::HTTP_OK);
    } */

    private function generateEmails(int $count = 10): array
    {
        $mails = [];
        $now = Carbon::now();
        for ($i = 0; $i < $count; $i++) {
            $mails[] = [
                'email' => $this->generateRandomString() . "@example.com",
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }
        return $mails;
    }

    private function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
