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
            <div class="col-lg-4">
                <canvas id="c" width="710" height="300"></canvas>
            </div>
            <div class="d-flex p-4">
                <button class="btn btn-sm btn-success" id="btnTanamBenang">Tanam Benang</button>
                &nbsp;
                <button class="btn btn-sm btn-success" id="btnFillerOval">Filler</button>
                &nbsp;
                <button class="btn btn-sm btn-success" id="btnFillerLittle">Tanam Benang</button>
                &nbsp;
                <button class="btn btn-sm btn-success" id="btnGelWajahMerata">Gel Wajah merata</button>
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

            canvas.setBackgroundImage('{{ asset('media/example.PNG') }}', canvas.renderAll.bind(canvas), {
                backgroundImageOpacity: 0.5,
                backgroundImageStretch: false,
            });

            canvas.on('mouseup',function (options) {
                alert("MANTAP")
            })

            $("#btnTanamBenang").on('click',function (e) {
                fabric.Image.fromURL('{{ asset('media/segitiga.png') }}',function (myImage) {
                    var img1 = myImage.set({ left: 0, top: 0 ,width:40,height:40});
                    canvas.add(img1)
                })
            })

            $("#btnFillerOval").on('click',function (e) {
                fabric.Image.fromURL('{{ asset('media/oval.png') }}',function (myImage) {
                    var img1 = myImage.set({ left: 0, top: 0 ,width:40,height:40});
                    canvas.add(img1)
                })
            })
        });
    </script>
@endsection
