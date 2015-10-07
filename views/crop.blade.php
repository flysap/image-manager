@extends('themes::layouts.default')

@section('content')
    <link  href="https://cdn.rawgit.com/fengyuanchen/cropper/v1.0.0-rc.1/dist/cropper.min.css" rel="stylesheet">
    <script src="https://cdn.rawgit.com/fengyuanchen/cropper/v1.0.0-rc.1/dist/cropper.min.js"></script>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">{{trans('Crop image')}}</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body table-responsive no-padding">
                        <div class="container">
                            {!! $image->render() !!}
                        </div>

                        <div class="btn-group">
                            <button type="button" class="btn btn-default" id="save">Save</button>
                            <button type="button" class="btn btn-default" id="reset">Reset</button>
                            <button type="button" class="btn btn-default" id="left">Rotate left</button>
                            <button type="button" class="btn btn-default" id="right">Rotate right</button>
                        </div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->

                <script type="text/javascript">
                    $(function() {
                        var $image = $('.container > img');

                        $image.cropper({
                            aspectRatio: 16 / 9
                        });

                        $("#left").click(function() {
                            $image.cropper('rotate', -45)
                        });

                        $("#right").click(function() {
                            $image.cropper('rotate', 45)
                        });

                        $("#reset").click(function() {
                            $image.cropper("reset");
                        });

                        $("#save").click(function() {
                            $.post('', $image.cropper('getData'), function() {
                                console.log(1)
                            })
                        });
                    })
                </script>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </section><!-- /.content -->
@endsection