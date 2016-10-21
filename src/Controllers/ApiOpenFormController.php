<?php

namespace Wisdom\Openform\Controllers;

use App\Http\Controllers\Controller;
use Wisdom\Openform\Models\OpenAnswer;
use Wisdom\Openform\Models\OpenForm;
use Illuminate\Http\Request;

use App\Http\Requests;

class ApiOpenFormController extends Controller
{
    protected function success($data)
    {
        $api = ['success' => true];
        if (is_array($data)) {
            $api = $api + $data;
        } else {
            $api['msg'] = $data;
        }

        return str_replace(['null', "NULL"], ['""', '""'], json_encode($api, true));

    }

    public function getForm($id)
    {
        $form = OpenForm::with('question.option')->find($id);
        $api_question = [];
        unset($form->app_id);
        foreach ($form->question as $question) {
            unset($question['form_id']);
            unset($question['question_id']);
        }

        return $this->success(['form' => $form]);
    }

    public function postAnswer(Request $request)
    {
//        $form = OpenForm::with('question.option')->find($request->form_id);
        foreach ($request->answers as $answer) {
            if ($answer['type'] == 'text') {
                OpenAnswer::create([
                    'option_id' => 0,
                    'question_id' => $answer['question_id'],
                    'value' => $answer['value'],
                ]);
            } elseif ($answer['type'] == 'single') {
                OpenAnswer::create([
                    'option_id' => $answer['value'],
                    'question_id' => $answer['question_id'],
                    'value' => $answer['value'],
                ]);
            } elseif ($answer['type'] == 'multiple') {
                foreach ($answer['values'] as $value) {
                    OpenAnswer::create([
                        'option_id' => $value,
                        'question_id' => $answer['question_id'],
                        'value' => $value,
                    ]);
                }
            }
        }
        return $this->success('Answer question complete !');
    }
}
