<?php
namespace App\Services\Referrals;
use App\Models\User;
use App\Models\ReferralContest;
use App\Models\Referral;
use App\Services\Referrals\ReferralContestConditionService;


class ReferralContestService{

    public $conditions;

    public function __construct(ReferralContestConditionService $conditions)
    {
        $this->conditions = $conditions;
    }


    public function findUsersInRange($id)
    {
        $referralContest = ReferralContest::findOrFail($id);

        // Get referrals within the contest date range
        $referrals = Referral::where('status', 'completed')
            ->whereBetween('created_at', [
                $referralContest->start_date,
                $referralContest->end_date
            ])
            ->with(['referrer', 'referredUser'])
            ->get();

        // Group by referrer and count their referrals
        $referrers = $referrals->groupBy('referrer_id')
            ->map(function ($referralGroup, $referrerId) {
                $referrer = $referralGroup->first()->referrer;
                $referrerUsername = $referrer ? $referrer->username : 'Unknown';

                return [
                    $referrerUsername => [
                        'total_referred' => $referralGroup->count(),
                        'users' => $referralGroup->map(function ($referral) {
                            return $referral->referredUser ? $referral->referredUser->username : 'Unknown';
                        })->filter()->toArray()
                    ]
                ];
            })
            ->collapse()
            ->toArray();

        return $referrers;
    }


    public function processReferrees(array $referrers)
    {
        foreach ($referrers as &$referrer) { // use reference to modify original
            foreach ($referrer['users'] as $key => $user) {
                if (!$this->conditions->hasNinOrBvn($user)) {
                    unset($referrer['users'][$key]); // remove just this user
                }
                if(!$this->conditions->hasFundedAccount($user)){
                    unset($referrer['users'][$key]); // remove just this user
                }
                if(!$this->conditions->hasBillableTransaction($user)){
                     unset($referrer['users'][$key]); // remove just this user
                }
            }

            // Re-index the users array (optional)
            $referrer['users'] = array_values($referrer['users']);

            // Add qualified_count based on remaining users
            $referrer['qualified_count'] = count($referrer['users']);
        }

        return $referrers;
    }


    public function sortReferrersByCount(array $referrers)
    {
        // Sort in descending order by total_referred
        uasort($referrers, function ($a, $b) {
            return $b['total_referred'] <=> $a['total_referred'];
        });

        return $referrers;
    }




}
