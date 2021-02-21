@extends('base.app')

@section('title', app_name() . ' | Mapping')

@section('css_after')
    <style>
        #c {
            background-color:gray
        }
    </style>
@endsection

@section('content')
    <style type="text/css">
        tfoot {
            display: table-header-group;
        }
    </style>
    <div class="block" id="my-block">
        <div class="block-header">

        </div>
        <div class="block-content block-content-full">
            <div class="row">
                <div class="col-lg-4">
                    <canvas id="c" width="300" height="300"></canvas>
                </div>
                <div class="col-lg-4 col-md-6">
                    <button class="btn btn-sm btn-success" id="btnTanamBenang">Tanam Benang</button><br><br>
                    <button class="btn btn-sm btn-success">Tanam Benang</button><br><br>
                    <button class="btn btn-sm btn-success">Tanam Benang</button><br><br>
                    <button class="btn btn-sm btn-success">Tanam Benang</button><br>

                </div>
            </div>

        </div>
    </div>
    <script src="{{ asset('js/fabric.min.js') }}"></script>
    <script>
        $(document).ready(function (){

            fabric.Object.prototype.set({
                transparentCorners: false,
                cornerColor: 'rgba(102,153,255,0.5)',
                cornerSize: 12,
                padding: 5
            });

            var canvas = window._canvas = new fabric.Canvas('c');

            canvas.setBackgroundImage('{{ asset('media/left-person.png') }}', canvas.renderAll.bind(canvas), {
                backgroundImageOpacity: 0.5,
                backgroundImageStretch: false,
            });

            $("#btnTanamBenang").on('click',function (e) {
                fabric.Image.fromURL('{{ asset('media/segitiga.png') }}',function (myImage) {
                    var img1 = myImage.set({ left: 0, top: 0 ,width:50,height:50});
                    canvas.add(img1)
                })
            })
        });
    </script>
@endsection
