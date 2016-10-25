@extends('openform.layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div id="openform-group">
                    <div class="panel panel-default">
                        <div class="panel-heading" style="border: 0">
                            <h3 class="panel-title">{{$report['title']}}</h3>
                        </div>
                        @foreach($report['question'] as $question)
                            <hr>
                            <div class="panel-body">
                                <div class="panel-heading">
                                    {{$question['title']}}
                                    <h6>{{$question['total']}} people answered this question</h6>
                                    <hr>

                                    @if($question['type']=='text')
                                        <table class="table black">
                                        @foreach($question['answers'] as $answer)
                                            <tr><td>{{$answer['value']}}</td></tr>
                                        @endforeach
                                        </table>
                                    @else
                                        @foreach($question['answers'] as $answer)
                                            <h6 class="black">{{$answer['title']}}</h6>
                                            <div class="progress">
                                                <?php $score = floatval(number_format($question['total']==0 ? 0 : $answer['value']/$question['total']*100, 2));?>
                                                <div class="progress-bar" role="progressbar" aria-valuenow="{{$score}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$score}}%;">
                                                    {{$score}}%
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <a href="{{url('form')}}" class="btn btn-default">Back</a><br><br><br>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="{{asset('css/style.css')}}">
@endsection
