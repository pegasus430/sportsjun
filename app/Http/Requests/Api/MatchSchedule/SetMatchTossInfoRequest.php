<?php

namespace App\Http\Requests\Api\MatchSchedule;


use App\Http\Requests\Api\BaseApiRequest;
use App\Model\MatchSchedule;
use Illuminate\Http\Exception\HttpResponseException;


class SetMatchTossInfoRequest extends BaseApiRequest
{

    public $matchSchedule;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $matchId = $this->route('id');
        $this->matchSchedule = $matchSchedule = MatchSchedule::findOrFail($matchId);

        $rules = [
            'toss_won_by'=>'required|integer',
            'fst_ing_batting'=>'required|integer',
            'scnd_ing_batting'=>'required|integer'
        ];


        return $rules;
    }


}
