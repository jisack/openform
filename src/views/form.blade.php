@extends('openform.layouts.app')

@section('content')
    <div class="container">
        <div id="row" class="row <?php echo isset($form->publish) && $form->publish == 1? 'uneditable':'';?>">
            <form class="form-horizontal" method="post"
                  action="{{isset($form) ? url('form/'.$form->id) : url('form')}}">
                <?php
                echo isset($form) ? "<input type='hidden' name='_method' value='put'>" : '';
                ?>
                <div class="col-md-10 col-md-offset-1">
                    <div id="openform-group">
                        <div class="panel panel-default">
                            <div class="panel-heading" style="border: 0">
                                <h3 class="panel-title">Project's Form</h3>
                            </div><hr>
                            {{csrf_field()}}
                            <div class="panel-body">
                                <div class="form-group">
                                    <label for="inputEmail3" class="col-sm-2 control-label">Title</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="title" class="form-control" id="inputEmail3"
                                               placeholder="title" value="{{isset($form->title) ? $form->title : ''}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputDescription3" class="col-sm-2 control-label">Description</label>
                                    <div class="col-sm-10">
                                    <textarea placeholder="description" name="description" class="form-control"
                                              rows="3">{{isset($form->description) ? $form->description : ''}}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if(isset($form->question))
                            <?php $question_count = 0 ?>
                            @foreach($form->question as $question)
                                <div class="panel panel-default" id="panel-question{{$question_count}}">
                                    <input type="hidden" value="{{$question->type}}" name="type[]">
                                    <input type="hidden" value="{{$question->id}}" name="question_id[]">
                                    <button type="button" onclick="removePanel('{{$question_count}}')" class="btn btn-danger btn-xs pull-right remove-button">
                                        <i class="fa fa-times-circle" aria-hidden="true"></i>
                                    </button><br>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <label for="inputEmail3" class="col-sm-2 control-label">Question
                                                Title</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="q_title[]" class="form-control"
                                                       id="inputEmail3" placeholder="title"
                                                       value="{{isset($question->title) ? $question->title : ''}}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="inputDescription3" class="col-sm-2 control-label">Question
                                                Description</label>
                                            <div class="col-sm-10">
                                            <textarea placeholder="description" name="q_description[]"
                                                      class="form-control"
                                                      rows="3">{{isset($question->description) ? $question->description : ''}}</textarea>
                                            </div>
                                        </div>
                                        @if($question->type == 'text')
                                            <div class="form-group">
                                                <label for="text" class="col-sm-2 control-label">Text</label>
                                                <div class="col-sm-10">
                                                    <input type="text" placeholder="text" name="text" disabled
                                                           class="form-control">
                                                </div>
                                            </div>
                                        @elseif($question->type == 'multiple')
                                            <div id="checkbox{{$question_count}}">
                                                @foreach($question->option as $option)
                                                    <input type="hidden" value="{{$option->id}}" name="option_id[]">
                                                    <div class="checkbox col-sm-offset-2">
                                                        <label class="col-sm-10">
                                                            <input type="checkbox" name="optionsRadios"
                                                                   style="margin-top:11px;" disabled value="option1">
                                                            <input type="text" class="form-control"
                                                                   name="multiple[{{$question_count}}][]"
                                                                   value="{{$option->title}}">
                                                        </label>
                                                    </div>
                                                    <br>
                                                @endforeach
                                            </div>
                                            <button class="add-choice btn btn-primary btn-sm pull-left col-sm-offset-2"
                                                    type="button" onclick="addCheckbox('{{$question_count}}')">Add
                                                Choice
                                            </button>
                                        @else
                                            <div id="radio{{$question_count}}">
                                                @foreach($question->option as $option)
                                                    <input type="hidden" value="{{$option->id}}" name="option_id[]">
                                                    <div class="radio col-sm-offset-2">
                                                        <label class="col-sm-10">
                                                            <input type="radio" name="optionsRadios"
                                                                   style="margin-top:11px;" disabled value="option1">
                                                            <input type="text" class="form-control"
                                                                   value="{{$option->title}}"
                                                                   name="single[{{$question_count}}][]">
                                                        </label>
                                                    </div><br>
                                                @endforeach
                                            </div>
                                            <button class="add-choice btn btn-primary btn-sm pull-left col-sm-offset-2"
                                                    type="button" onclick="addRadio('{{$question_count}}')">Add Choice
                                            </button>
                                        @endif
                                    </div>
                                </div>
                                <?php $question_count++ ?>
                            @endforeach
                        @endif
                    </div>
                    <button type="button" class="add-question btn btn-primary pull-right" data-toggle="modal"
                            data-target="#add-item">Add Question
                    </button>
                    <br><br><br>
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="pull-left">
                                @if(isset($form->publish) && $form->publish == 1 )
                                    <button class="btn btn-danger" disabled type="button">Published : {{$form->published_date}}</button>
                                @else
                                    <button type="button" id="publish" class="btn btn-danger">Publish</button>
                                @endif
                            </div>
                            <div class="pull-right">
                                <a href="{{url('form')}}" class="btn btn-default">Cancel</a>
                                <button type="submit" id="btn-submit" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </div>
                    <br><br>
                </div>
            </form>
        </div>
        <div class="modal fade" id="add-item" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Modal title</h4>
                    </div>
                    <div class="modal-body  text-center" style="min-height: 190px">
                        <div class="col-sm-4">
                            <a href="#" id="form-checkbox">
                                <img src="{{asset('openform-images/icon_input_checkboxes.png')}}">
                                <br><br>Multiple Choice<br><br>
                            </a>
                        </div>
                        <div class="col-sm-4">
                            <a href="#" id="form-radio">
                                <img src="{{asset('openform-images/icon_input_multiplechoice.png')}}">
                                <br><br>Single Choice<br><br>
                            </a>

                        </div>
                        <div class="col-sm-4">
                            <a href="#" id="form-text">
                                <img src="{{asset('openform-images/icon_input_text.png')}}">
                                <br><br>Text<br><br>
                            </a>
                        </div>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div>
    <script src="http://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script>
        var question = {{isset($question_count) ? $question_count : 0 }};
        $("#form-checkbox").click(function () {
            $("#openform-group").append('<div class="panel panel-default" id="panel-question' + question + '"><button type="button" onclick="removePanel(' + question + ')" class="btn btn-danger btn-xs pull-right remove-button">' +
                    '<i class="fa fa-times-circle" aria-hidden="true"></i></button><br>' +
                    '<div class="panel-body">' +
                    '<input type="hidden" name="type[' + question + ']" value="multiple"><div class="form-group">' +
                    '<label for="inputEmail3" class="col-sm-2 control-label">Question Title</label>' +
                    '<div class="col-sm-10">' +
                    '<input type="text" class="form-control" name="q_title[' + question + ']" id="inputEmail3" placeholder="question title">' +
                    '</div>' +
                    '</div>' +
                    '<div class="form-group">' +
                    '<label for="inputDescription3" class="col-sm-2 control-label">Description</label>' +
                    '<div class="col-sm-10">' +
                    '<textarea placeholder="description" name="q_description[' + question + ']" class="form-control"' +
                    ' rows="3"></textarea>' +
                    '</div>' +
                    '</div>' +
                    '<div id="checkbox' + question + '">' +
                    '<div class="checkbox col-sm-offset-2">' +
                    '<label class="col-sm-10">' +
                    '<input type="checkbox" name="optionsRadios" style="margin-top:11px;" disabled value="option1">' +
                    '<input type="text" class="form-control" name="multiple[' + question + '][]">' +
                    '</label>' +
                    '</div><br>' +
                    '</div>' +
                    '<button class="add-choice btn btn-primary btn-sm pull-left col-sm-offset-2" type="button" onclick="addCheckbox(' + question + ')">Add Choice</button>' +
                    '</div>' +
                    '</div>' +
                    '</div></div>');
            $("#add-item").modal('hide');
            question++;
        });

        $("#form-radio").click(function () {
            $("#openform-group").append('<div class="panel panel-default" id="panel-question' + question + '"><button type="button" onclick="removePanel(' + question + ')" class="btn btn-danger btn-xs pull-right remove-button">' +
                    '<i class="fa fa-times-circle" aria-hidden="true"></i></button><br>' +
                    '<div class="panel-body">' +
                    '<input type="hidden" name="type[' + question + ']" value="single"><div class="form-group">' +
                    '<label for="inputEmail3" class="col-sm-2 control-label">Question Title</label>' +
                    '<div class="col-sm-10">' +
                    '<input type="text" class="form-control"  name="q_title[' + question + ']" id="inputEmail3" placeholder="question title">' +
                    '</div>' +
                    '</div>' +
                    '<div class="form-group">' +
                    '<label for="inputDescription3" class="col-sm-2 control-label">Description</label>' +
                    '<div class="col-sm-10">' +
                    '<textarea placeholder="description" name="q_description[' + question + ']" class="form-control"' +
                    ' rows="3"></textarea>' +
                    '</div>' +
                    '</div>' +
                    '<div id="radio' + question + '">' +
                    '<div class="radio col-sm-offset-2">' +
                    '<label class="col-sm-10">' +
                    '<input type="radio" name="optionsRadios" style="margin-top:11px;" disabled value="option1">' +
                    '<input type="text" class="form-control" name="single[' + question + '][]">' +
                    '</label>' +
                    '</div><br>' +
                    '</div>' +
                    '<button class="add-choice btn btn-primary btn-sm pull-left col-sm-offset-2" type="button" onclick="addRadio(' + question + ')">Add Choice</button>' +
                    '</div>' +
                    '</div>' +
                    '</div>');
            $("#add-item").modal('hide');
            question++;
        });

        $("#form-text").click(function () {
            $("#openform-group").append('<div class="panel panel-default" id="panel-question' + question + '"><button type="button" onclick="removePanel(' + question + ')" class="btn btn-danger btn-xs pull-right remove-button">' +
                    '<i class="fa fa-times-circle" aria-hidden="true"></i></button><br>' +
                    '<div class="panel-body">' +
                    '<input type="hidden" name="type[' + question + ']" value="text"><div class="form-group">' +
                    '<label for="inputEmail3" class="col-sm-2 control-label">Question Title</label>' +
                    '<div class="col-sm-10">' +
                    '<input type="text" class="form-control"  name="q_title[' + question + ']" id="inputEmail3" placeholder="question title">' +
                    '</div>' +
                    '</div>' +
                    '<div class="form-group">' +
                    '<label for="inputDescription3" class="col-sm-2 control-label">Description</label>' +
                    '<div class="col-sm-10">' +
                    '<textarea placeholder="description" name="q_description[' + question + ']" class="form-control"' +
                    ' rows="3"></textarea>' +
                    '</div>' +
                    '</div>' +
                    '<div class="form-group">' +
                    '<label for="text" class="col-sm-2 control-label">Text</label>' +
                    '<div class="col-sm-10">' +
                    '<input type="text" placeholder="text" name="text" disabled class="form-control">' +
                    '</div>' +
                    '</div>' +
                    '</div></div>');
            $("#add-item").modal('hide');
            question++;
        });

        function addRadio(question_id) {
            $('#radio' + question_id).append('<div class="radio col-sm-offset-2"><label class="col-sm-10">' +
                    '<input type="radio" style="margin-top:11px;" disabled value="option1">' +
                    '<input type="text" class="form-control" name="single[' + question_id + '][]">' +
                    '</label></div><br>')
        }

        function addCheckbox(question_id) {
            $('#checkbox' + question_id).append('<div class="checkbox col-sm-offset-2"><label class="col-sm-10">' +
                    '<input type="checkbox" style="margin-top:11px;" disabled value="option1">' +
                    '<input type="text" class="form-control" name="multiple[' + question_id + '][]">' +
                    '</label></div><br>')
        }

        function removePanel(question_id) {
            $('#panel-question' + question_id).remove();
        }

        $('#publish').click(function () {
            @if(isset($form))
            if ($(this).attr('class') == 'btn btn-danger') {
                $.ajax({
                    url: '{{url("form/publish/".$form->id)}}',
                    method: 'get',
                    success: function (data) {
                        $('#publish').html('Published : '+data).attr('disabled',true);
                        $('.remove-button').remove();
                    }
                });
            }
            @else
            validate(function() {
                $('form').append('<input type="hidden" value="1" name="publish">').submit();
            });
            @endif
        });

        function validate(callback){
            var json = {};
            var textbox = $(':text');
            if($('#openform-group').children().length <= 1){
                alert('Needs at least one question');
                return false;
            }
            for (var i = 0; i < textbox.length; i++) {
                if (textbox.eq(i).val() == '' && textbox.eq(i).attr('disabled') == false) {
                    alert('Empty Field');
                    return false;
                }
            }
            textbox = $('textarea');
            for (var i = 0; i < textbox.length; i++) {
                if (textbox.eq(i).val() == '') {
                    alert('Empty Field');
                    return false;
                }
            }
            callback();
        }

        $('form').on('submit', function () {
            return validate();
        });
    </script>

    <link rel="stylesheet" href="{{asset('openform/assets/style.css')}}">
@endsection
