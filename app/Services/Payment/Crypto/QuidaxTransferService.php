<?php
namespace App\Services\Payment\Crypto;

use DateTimeImmutable;
use DateTimeZone;
use App\Services\Payment\Crypto\QuidaxxService;

class QuidaxTransferService
{
    protected $quidaxService; 

    public function __construct(QuidaxxService $quidaxService)
    {
        $this->quidaxService = $quidaxService;
    }

    /**
     * Generate a timestamp-based high-entropy reference.
     * Format: qdx-YYYYMMDDTHHMMSS.mmmuuuZ-<base64url>
     * Entropy sources: cryptographic RNG (192 bits) + hrtime() + timestamp (+ optional user id)
     */
    private function generateReference(?string $userQuidaxId = null, string $prefix = 'qdx'): string
    {
        $ts = (new DateTimeImmutable('now', new DateTimeZone('UTC')))
            ->format('Ymd\THis.u\Z'); // includes microseconds

        // 192 bits of randomness
        $rand = random_bytes(24);

        // High-res monotonic clock adds extra uniqueness across rapid calls
        $nanos = (string) \hrtime(true);

        // Build payload and hash to compress it to a short, fixed, URL-safe token
        $payload = $ts . '|' . ($userQuidaxId ?? '-') . '|' . $nanos . '|' . bin2hex($rand);

        // Use app key if available; fall back to plain hash
        $key = \config('app.key', '');
        $digest = $key
            ? hash_hmac('sha256', $payload, $key, true)
            : hash('sha256', $payload, true);

        // Take 15 bytes (~120 bits) -> base64url -> ~20 chars; plenty of entropy
        $token = self::base64url(substr($digest, 0, 15));

        return sprintf('%s-%s-%s', $prefix, $ts, $token);
    }

    private static function base64url(string $bin): string
    {
        return rtrim(strtr(base64_encode($bin), '+/', '-_'), '=');
    }

    /**
     * Transfer funds via Quidax
     *
     * @param string $amount
     * @param string $currency
     * @param string $transactionNote
     * @param string $narration
     * @param string $targetAddressOrQuidaxId Wallet address or sub-account
     * @param string $userQuidaxId            Auth user's Quidax unique identifier (for the path)
     * @param string|null $network            (Optional) Blockchain network
     * @return mixed
     */
    public function transferFunds(
        float $amount,
        string $currency,
        string $transactionNote,
        string $narration,
        string $reciever,
        string $senderQuidaxId,
        ?string $network = null
    ) {
        // dd($this->generateReference($reciever));
        $payload = [
            'currency'         => $currency,
            'amount'           => $amount,
            'transaction_note' => $transactionNote,
            'narration'        => $narration,
            'fund_uid'         => $reciever,//parentwalletId
            'reference'        => $this->generateReference($reciever), 
        ];
        // dd($payload);

        if ($network !== null) {
            $payload['network'] = $network;
        }

        return $this->quidaxService->makeRequest(
            'post',
            "/users/{$senderQuidaxId}/withdraws",
            $payload
        );
    }
}
