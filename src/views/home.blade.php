@extends('openform.layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading" style="border: 0">
                    Your 's Form
                    <a href="{{url('form/create')}}" class="btn btn-primary pull-right">Create Form</a><br><br>
                </div>

                <div class="panel-body">
                    <table class="table">
                        {{--<tr>--}}
                            {{--<td>Title</td>--}}
                            {{--<td></td>--}}
                        {{--</tr>--}}
                    @foreach($forms as $form)
                        <tr>
                            <td width="50">
                                {{$form->id}}
                            </td>
                            <td >
                                {{$form->title}}
                            </td>
                            <td style="text-align: right">
                                <a href="{{url('openform/api/form/'.$form->id)}}" class="btn btn-primary btn-sm" ><i class="fa fa-exchange"></i></a>
                                <a href="{{url('form/'.$form->id.'/edit')}}" class="btn btn-primary btn-sm" ><i class="fa fa-pencil"></i></a>
                                <a href="{{url('form/report/'.$form->id)}}" class="btn btn-primary btn-sm" ><i class="fa fa-bar-chart"></i></a>
                            </td>
                        </tr>
                    @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
