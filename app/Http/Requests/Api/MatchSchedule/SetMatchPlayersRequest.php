<?php

namespace App\Http\Requests\Api\MatchSchedule;


use App\Http\Requests\Api\BaseApiRequest;
use App\Model\MatchSchedule;
use Illuminate\Http\Exception\HttpResponseException;


class SetMatchPlayersRequest extends BaseApiRequest
{
    public $matchSchedule;
    public $fields = [
        'a' => [
            'players_a_main' => 1,
            'players_a_sub'  => 0,
        ],
        'b' => [
            'players_b_main' => 1,
            'players_b_sub'  => 0
        ]
    ];

    public $errorMessages = [];
    public $data;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        return $this->errorMessages;
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
        if ($matchSchedule->schedule_type != 'team') {
            throw new HttpResponseException($this->response(
                ["Set players only for team matches" => 0]
            ));
        }

        $players = [
            'a' => $matchSchedule->player_a_ids,
            'b' => $matchSchedule->player_b_ids
        ];

        $all = $this->all();
        $rules = [];
        foreach ($this->fields as $group_key => $field_group) {
            foreach ($field_group as $field => $required) {
                $count = count($all[$field]);
                for ($i = 0; $i < $count; $i++) {
                    $rules[$field . '.' . $i] = ($required ? 'required|' : "")
                        . 'integer|in:' . $players[$group_key];

                    $this->errorMessages[$field . '.' . $i . '.in'] = 'Player should be in list - ' . $players[$group_key];
                }
            }
        }

        return $rules;
    }

    public function all()
    {
        $all = parent::all();
        $result = [];
        foreach ($this->fields as $field_group) {
            foreach ($field_group as $field => $required)
                $result[$field] = explode(',', array_get($all, $field, ''));
        }
        $this->data = $result;
        return $result;
    }


}
