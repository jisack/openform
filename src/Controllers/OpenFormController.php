<?php

namespace Wisdom\Openform\Controllers;

use App\Http\Controllers\Controller;
use Wisdom\Openform\Models\OpenAnswer;
use Wisdom\Openform\Models\OpenForm;
use Wisdom\Openform\Models\OpenOption;
use Wisdom\Openform\Models\OpenQuestion;
use Illuminate\Http\Request;

use App\Http\Requests;

class OpenFormController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('openform.home', ['forms' => OpenForm::paginate(15)]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('openform.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

//        dd($request->all());
        if (isset($request->publish)) {
            $publish = 1;
            $published_date = date("Y-m-d H:i:s");
        } else {
            $publish = 0;
            $published_date = null;
        }
        $res = OpenForm::create([
            'title' => $request->title,
            'description' => $request->description,
            'publish' => $publish,
            'published_date' => $published_date
        ]);

        $requestArray = $request->all();
        if ($res) {
            foreach ($request->q_title as $key => $question) {
                $quest = OpenQuestion::create([
                    'title' => $question,
                    'description' => $requestArray['q_description'][$key],
                    'type' => $requestArray['type'][$key],
                    'form_id' => $res->id
                ]);
                if ($quest && $requestArray['type'][$key] != 'text') {
                    foreach ($requestArray[$requestArray['type'][$key]][$key] as $option_key => $value) {
                        OpenOption::create([
                            'title' => $value,
                            'question_id' => $quest->id
                        ]);
                    }
                }
            }
            return redirect('form');
        } else {
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $form = OpenForm::with('question.option')->find($id);
        return view('openform.form', ['form' => $form]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $form = OpenForm::find($id);
        $res = OpenForm::find($id)->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);
        $requestArray = $request->all();
        if ($res) {
            if ($form->publish) {
                foreach ($request->question_id as $key => $question) {
//                    dd($question);
                    $quest = OpenQuestion::find($question)->update([
                        'title' => $requestArray['q_title'][$key],
                        'description' => $requestArray['q_description'][$key],
                        'type' => $requestArray['type'][$key],
                        'form_id' => $id
                    ]);
                    if ($quest && $requestArray['type'][$key] != 'text') {
                        foreach ($request->option_id as $option_key => $value) {
                            OpenOption::find($value)->update([
                                'title' => $requestArray[$requestArray['type'][$key]][$key][$option_key],
                                'question_id' => $question
                            ]);
                        }
                    }
                }
            } else {
                OpenQuestion::where('form_id', $id)->delete();
                foreach ($request->q_title as $key => $question) {
                    $quest = OpenQuestion::create([
                        'title' => $question,
                        'description' => $requestArray['q_description'][$key],
                        'type' => $requestArray['type'][$key],
                        'form_id' => $id
                    ]);
                    if ($quest && $requestArray['type'][$key] != 'text') {
                        foreach ($requestArray[$requestArray['type'][$key]][$key] as $option_key => $value) {
                            OpenOption::create([
                                'title' => $value,
                                'question_id' => $quest->id
                            ]);
                        }
                    }
                }
            }


            return redirect('form');
        } else {
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function publish($id)
    {
        $res = OpenForm::find($id)->update(['publish' => 1, 'published_date' => date("Y-m-d H:i:s")]);
        if ($res) {
            return date("Y-m-d H:i:s");
        } else {
            return 'failed';
        }
    }

    public function report($id)
    {
        $form = OpenForm::with('question.option')->with('question.answer')->find($id);
        $report = [
            'form_id' => $form->id,
            'title' => $form->title,
        ];
//        dd($form->toArray());
        foreach ($form->question as $question) {
            if ($question['type'] == 'text') {
                $report['question'][] = [
                    'title' => $question['title'],
                    'description' => $question['description'],
                    'type' => $question['type'],
                    'total' => count($question['answer']),
                    'answers' => $question['answer']
                ];
            } elseif ($question['type'] == 'single') {
                $options = [];
                foreach ($question['option'] as $single_option) {
                    $options[$single_option['id']] = ['title' => $single_option['title'], 'value' => 0];
                }
                foreach ($question['answer'] as $single_answer) {
                    $options[$single_answer['option_id']]['value']++;
                }
                $report['question'][] = [
                    'title' => $question['title'],
                    'description' => $question['description'],
                    'type' => $question['type'],
                    'total' => count($question['answer']),
                    'answers' => $options,
                ];
            } elseif ($question['type'] == 'multiple') {
                $options = [];
                foreach ($question['option'] as $multiple_option) {
                    $options[$multiple_option['id']] = ['title' => $multiple_option['title'], 'value' => 0];
                }
                foreach ($question['answer'] as $multiple_answer) {
                    $options[$multiple_answer['option_id']]['value']++;
                }
                $report['question'][] = [
                    'title' => $question['title'],
                    'description' => $question['description'],
                    'type' => $question['type'],
                    'total' => count($question['answer']),
                    'answers' => $options,
                ];
            }
        }

//        dd($report);

        return view('openform.report', ['report' => $report]);

    }


}
