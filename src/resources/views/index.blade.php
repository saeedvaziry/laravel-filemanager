<html>
<head>
    <meta charset="UTF-8">
    <title>{{ trans('filemanager::filemanager.filemanager') }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css">
</head>
<body>
    <div class="container" style="margin-top:10px;">
        <div class="row">
            <div class="col-lg-12">
              <form method="post" action="{{ url(config('filemanager.basicRoute').'/upload') }}" id="form_upload" enctype="multipart/form-data">
                  {{ csrf_field() }}
                  <input type="file" name="file" id="file" style="display:none;">
                  <a href="#" id="btn_select_file" class="btn btn-success">
                    {{ trans('filemanager::filemanager.select_file') }}
                  </a>
                  <input type="submit" name="btn_upload" class="btn btn-primary" value="{{ trans('filemanager::filemanager.upload') }}" >
              </form>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                @if(count($errors) == 1)
                <div class="alert alert-danger">
                  @foreach ($errors->all() as $error)
                    {{ $error }}
                  @endforeach
                </div>
                @elseif(count($errors) > 1)
                <div class="alert alert-danger">
                  <ul>
                    @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                  </ul>
                </div>
                @endif
                @if(session('alert'))
                <div class="alert alert-{{ session('alert') }}">
                  {{ session('message') }}
                </div>
                @endif
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-file"></i> {{ trans('filemanager::filemanager.files') }}
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-advance table-hover">
                                <thead>
                                    <tr>
                                        <th>{{ trans('filemanager::filemanager.file_name') }}</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($files as $f)
                                    <tr>
                                        <td><a href="javascript:;" data-toggle="popover" data-placement="right" data-img="@if(in_array($f['type'],$imageTypes)) {{ url('uploads/'.$f['file']->getRelativePathname()) }} @endif">{{ $f['file']->getRelativePathname() }}</a></td>
                                        <td>
                                            <a href="#" class="btn btn-success btn-xs btn_use_file" data-file="{{ url($f['file']) }}" data-filename="{{ $f['fileName'] }}">{{ trans('filemanager::filemanager.use_file') }}</a>
                                            <a href="{{ asset(config('filemanager.uploadDir').'/'.$f['file']->getRelativePathname()) }}" class="btn btn-primary btn-xs">{{ trans('filemanager::filemanager.download') }}</a>
                                            <a href="{{ url(config('filemanager.basicRoute').'/delete?name='.$f['file']->getRelativePathname()) }}" class="btn btn-danger btn-xs btn_show_confirm">{{ trans('filemanager::filemanager.delete') }}</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="panel-footer">
                        {!! $files->render() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            function getParameterByName(name) {
                name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
                var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
                    results = regex.exec(location.search);
                return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
            }
            var CKEditor = getParameterByName("CKEditor");
            var type = getParameterByName("type");
            if(CKEditor.length > 0 || type.length > 0)
                $('.navbar').remove();
            else
                $('.btn_use_file').remove();
            $('#btn_select_file').click(function(e){
                e.preventDefault();
                $('#file').trigger('click');
            });
            $('[data-toggle="popover"]').popover({
              html: true,
              trigger: 'hover',
              content: function () {
                return '<img src="'+$(this).data('img') + '" width="200" height="200" />';
              }
            });
            $('.btn_use_file').click(function(e){
                e.preventDefault();
                useFile($(this).data('file'),$(this).data('filename'));
            });
            function useFile(file,name) {
                function getUrlParam(paramName) {
                    var reParam = new RegExp('(?:[\?&]|&)' + paramName + '=([^&]+)', 'i');
                    var match = window.location.search.match(reParam);
                    return (match && match.length > 1) ? match[1] : null;
                }
                var select = getUrlParam('select');
                if(!select){
                    var funcNum = getUrlParam('CKEditorFuncNum');
                    window.opener.CKEDITOR.tools.callFunction(funcNum,file);
                    window.close();
                }
                else{
                    var url = file;
                    window.opener.$('#hidden_image').val(name);
                    window.opener.$('.image_area').empty().append("<hr><div class='thumbnail'><img src='" + url + "' width='80'/></div>");
                    window.opener.$('#btn_delete_image').show();
                    window.close();
                }
            }
        });
    </script>
</body>
</html>
