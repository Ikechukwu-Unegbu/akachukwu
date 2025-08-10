<?php 
namespace App\Services\Referral;

use App\Services\Referral\ReferralContestConditionService;

class ReferralContestService{

    public $conditions;

    public function __construct(ReferralContestCondtionService $conditions)
    {
        $this->conditions = $conditions;
    }

   
    public function findUsersInRange($id)
    {
        $referralContest = ReferralContest::findOrFail($id);

        $users = User::whereNotNull('referer_username')
            ->whereBetween('created_at', [
                $referralContest->start_date,
                $referralContest->end_date
            ])
            ->get()
            ->groupBy('referer_username')
            ->map(function ($group) {
                return [
                    'total_referred' => $group->count(),
                    'users' => $group->pluck('username')->toArray()
                ];
            });

        return $users;
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