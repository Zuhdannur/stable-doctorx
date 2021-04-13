@extends('backend.layouts.app')

@section('title', __('patient::labels.prescription.management') . ' | ' . __('patient::labels.prescription.create'))

@section('css_after')
    <link rel="stylesheet" href="{{ URL::asset('css/photoswipe.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/default-skin/default-skin.css') }}">
    <style>
        .my-gallery {
            width: 100%;
        }

        .my-gallery img {
            width: 100%;
            height: auto;
        }

        .my-gallery figure {
            margin: 0 5px 5px 0;
            width: 150px;
        }

        .my-gallery figcaption {
            display: none;
        }
    </style>
@endsection

@section('content')
    <div class="row row-deck">
        <div class="col-lg-12">
            <div class="block block-rounded my-block">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Data Pasien</h3>
                    <div class="block-options">
                        <a href="{{ route('admin.patient.appointment.index') }}"
                           class="btn btn-sm btn-secondary mr-5 mb-5">
                            <i class="fa fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="block-content">
                    <table class="table table-borderless table-sm table-striped">
                        <tbody>
                        <tr>
                            <td class="text-left">
                                <span class="text-muted">APP ID</span>
                            </td>
                            <td class="text-right">
                                <span
                                    class="text-black text-primary font-w600">{{ $appointment->appointment_no }}</span>
                            </td>
                            <td class="text-left">
                                <span class="text-muted">Pasien ID</span>
                            </td>
                            <td class="text-right">
                                <span
                                    class="text-black text-primary font-w600">{{ $appointment->patient->patient_unique_id }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left">
                                <span class="text-muted">Pasien</span>
                            </td>
                            <td class="text-right">
                                <span
                                    class="text-black text-primary font-w600">{{ $appointment->patient->patient_name }}</span>
                            </td>
                            <td class="text-left">
                                <span class="text-muted">Usia</span>
                            </td>
                            <td class="text-right">
                                <span class="text-black text-primary font-w600">{{ $appointment->patient->age }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left">
                                <span class="text-muted">Hobi</span>
                            </td>
                            <td class="text-right">
                                <span
                                    class="text-black text-primary font-w600">{{ $appointment->patient->hobby }}</span>
                            </td>
                            <td class="text-left">
                                <span class="text-muted">Telepon</span>
                            </td>
                            <td class="text-right">
                                <span
                                    class="text-black text-primary font-w600">{{ $appointment->patient->phone_number }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left">
                                <span class="text-muted">Tgl Lahir</span>
                            </td>
                            <td class="text-right">
                                <span class="text-black text-primary font-w600">{{ $appointment->patient->dob }}</span>
                            </td>
                            <td class="text-left">
                                <span class="text-muted">Jenis Kelamin</span>
                            </td>
                            <td class="text-right">
                                <span
                                    class="text-black text-primary font-w600">{{ $appointment->patient->gender }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left">
                                <span class="text-muted">Gol. Darah</span>
                            </td>
                            <td class="text-right">
                                <span
                                    class="text-black text-primary font-w600">{{ $appointment->patient->blood->blood_name }}</span>
                            </td>
                            <td class="text-left">
                                <span class="text-muted">Flag</span>
                            </td>
                            <td class="text-right">
                                <span
                                    class="text-black text-primary font-w600">{{ $appointment->patient->flag['name'] }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left" colspan="6">
                                <span class="text-muted">Problem: {{ $appointment->notes }}</span>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <form method="post" id="prescription_form" class="js-validation-bootstrap" autocomplete="off"
          enctype="multipart/form-data">
        <input type="hidden" name="appid" value="{{ $appointment->id }}">
        <div class="block my-block" id="my-block2">
            <div class="block-header block-header-default">
                <h3 class="block-title">Catatan</h3>
            </div>
            <div class="block-content block-content-full">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            {{ html()->label('Keluhan')
                                ->class('col-md-2 form-control-label')
                                ->for('code') }}
                            <div class="col-md-10">
                                <textarea class="form-control js-simplemde" id="complaint" name="complaint"
                                          required="required" value=""></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            {{ html()->label('Riwayat Perawatan dan Alergi')
                                ->class('col-md-2 form-control-label')
                                ->for('code') }}
                            <div class="col-md-10">
                                <textarea class="form-control js-simplemde" id="treatment_history"
                                          name="treatment_history"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="block my-block" id="my-block2">
            <div class="block-header block-header-default">
                <h3 class="block-title">Resep/Obat</h3>
            </div>
            <div class="block-content block-content-full">
                <div class="row clearfix">
                    <div class="col-md-12">
                        <span id="error"></span>
                        <table class="table table-bordered table-hover table-striped table-vcenter" id="tab_logic">
                            <thead>
                            <tr>
                                <th class="text-center" style="width: 50px;"></th>
                                <th style="width: 50px;"> #</th>
                                <th> Resep/Obat</th>
                                <th style="width:150px"> Qty</th>
                                <th> Catatan</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>
                                    <button type="button" class="btn btn-sm btn-circle btn-outline-success add"
                                            title="Tambah Item">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </td>
                                <td class="nomor">
                                    1
                                </td>
                                <td>
                                    <select class="form-control product" id="product1" name="product[1]"
                                            data-placeholder="Pilih" style="width: 100%">
                                        {!! $product !!}
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name='qty[1]' class="form-control qty" value="1" min="1"/>
                                </td>
                                <td>
                                    <input type="text" name='instruction[1]' class="form-control instruction"/>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="block my-block" id="my-block3">
            <div class="block-header block-header-default">
                <h3 class="block-title">Tindakan & Catatan</h3>
            </div>
            <div class="block-content block-content-full">
                <div class="form-group row">
                    {{ html()->label("Daftar Treatment")
                        ->class('col-md-2 form-control-label')
                        ->for('is_treatment') }}

                    <div class="col-md-10">
                        <input type="checkbox" id="is_treatment" name="is_treatment" class="switch-input"
                               checked="checked">
                        <label for="is_treatment" class="switch-label"><span class="toggle--on">Ya</span><span
                                class="toggle--off">Tidak</span></label>
                    </div><!--col-->
                </div><!--form-group-->
                <div class="row clearfix">
                    <div class="col-md-12">
                        <span id="error"></span>
                        <table class="table table-bordered table-hover table-striped table-vcenter" id="tab_logic3">
                            <thead>
                            <tr>
                                <th class="text-center" style="width: 50px;"></th>
                                <th style="width: 50px;"> #</th>
                                <th> Tindakan</th>
                                <th style="width:150px"> Qty</th>
                                <th> Keterangan</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>
                                    <button type="button" class="btn btn-sm btn-circle btn-outline-success add3"
                                            title="Tambah Tindakan">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </td>
                                <td class="nomor3">
                                    1
                                </td>
                                <td>
                                    <select class="form-control service" id="service1" name="service[1]"
                                            data-placeholder="Pilih" style="width: 100%">
                                        {!! $service !!}
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name='qty[1]' class="form-control qty" value="1" min="1"/>
                                </td>
                                <td>
                                    <input type="text" name='servicenotes[1]' class="form-control servicenotes"/>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="block my-block" id="my-block">
            <div class="block-header block-header-default">
                <h3 class="block-title">Diagnosa</h3>
            </div>
            <div class="block-content block-content-full">
                <div class="row clearfix">
                    <div class="col-md-12">
                        <span id="error"></span>
                        <table class="table table-bordered table-hover table-striped table-vcenter" id="tab_logic2">
                            <thead>
                            <tr>
                                <th class="text-center" style="width: 50px;"></th>
                                <th style="width: 50px;"> #</th>
                                <th> Diagnosa</th>
                                <th> Catatan</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>
                                    <button type="button" class="btn btn-sm btn-circle btn-outline-success add2"
                                            title="Tambah Item">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                </td>
                                <td class="nomor2">
                                    1
                                </td>
                                <td>
                                    <select name="diagnosis[1]" id="1diagnosis2" class="form-control diagnosis"
                                            data-placeholder="Pilih" style="width: 100%">
                                        <option></option>
                                        {!! $datadiagnoseitem !!}
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name='diaginstruction[1]' class="form-control diaginstruction"/>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="block my-block" id="my-block2">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Jadwal Konsultasi Lanjutan</h3>
                    </div>
                    <div class="block-content block-content-full">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <div class="col-4">
                                        <label for="mega-lastname">Tanggal</label>
                                        <input type="text" class="form-control datepicker" id="next_appointment_date"
                                               name="next_appointment_date" value="{{ old('date') }}">
                                    </div>
                                    <div class="col-8">
                                        <label for="mega-firstname">Catatan</label>
                                        <input type="text" class="form-control" id="next_appointment_notes"
                                               name="next_appointment_notes" value="{{ old('date') }}">
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="block my-block" id="my-block2">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Foto Before (Saat Ini)</h3>
                        <div class="block-options">
                            <button type="button" onclick="document.getElementById('file-input-foto').click();"
                                    class="btn btn-sm btn-success mr-5 mb-5">
                                <i class="fa fa-plus mr-5"></i>Tambah Foto
                            </button>
                            <input id="file-input-foto" type="file" name="file-input-foto" style="display: none;"
                                   accept="image/x-png,image/jpeg"/>
                            <button type="button" class="btn btn-sm btn-info mr-5 mb-5" data-toggle="modal"
                                    data-target="#modal-webcam" data-backdrop="static" id="openCamera">
                                <i class="fa fa-camera"></i> Webcam
                            </button>
                        </div>
                    </div>
                    <div class="block-content block-content-full">
                        <div class="row my-gallery mb-2" id="imageGrid" itemscope
                             itemtype="http://schema.org/ImageGallery">
                            @if(isset($patient->beforeafter))
                                @foreach($patient->beforeafter as $item)
                                    @if(!empty($item->image))
                                    <div class="col-md-2 animated fadeIn">
                                        <div class="options-container">
                                            <figure itemprop="associatedMedia" itemscope
                                                    itemtype="http://schema.org/ImageObject">
                                                <a href="{{ URL::asset($item->image)  }}" itemprop="contentUrl" data-size="1024x1024">
                                                    <img src="{{ URL::asset($item->image) }}" itemprop="thumbnail"
                                                         alt="Image description"/>
                                                </a>
                                                <figcaption itemprop="caption description">Image caption 1</figcaption>
                                            </figure>
                                        </div>
                                    </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                        <div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
                            <select style="z-index: 9999999"></select>
                            <!-- Background of PhotoSwipe.
                                 It's a separate element, as animating opacity is faster than rgba(). -->
                            <div class="pswp__bg"></div>

                            <!-- Slides wrapper with overflow:hidden. -->
                            <div class="pswp__scroll-wrap">

                                <!-- Container that holds slides. PhotoSwipe keeps only 3 slides in DOM to save memory. -->
                                <!-- don't modify these 3 pswp__item elements, data is added later on. -->
                                <div class="pswp__container">
                                    <div class="pswp__item"></div>
                                    <div class="pswp__item"></div>
                                    <div class="pswp__item"></div>
                                </div>

                                <!-- Default (PhotoSwipeUI_Default) interface on top of sliding area. Can be changed. -->
                                <div class="pswp__ui pswp__ui--hidden">

                                    <div class="pswp__top-bar">

                                        <!--  Controls are self-explanatory. Order can be changed. -->

                                        <div class="pswp__counter"></div>

                                        <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>

                                        <button class="pswp__button pswp__button--share" title="Share"></button>

                                        <button class="pswp__button pswp__button--fs"
                                                title="Toggle fullscreen"></button>

                                        <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>

                                        <!-- Preloader demo https://codepen.io/dimsemenov/pen/yyBWoR -->
                                        <!-- element will get class pswp__preloader--active when preloader is running -->
                                        <div class="pswp__preloader">
                                            <div class="pswp__preloader__icn">
                                                <div class="pswp__preloader__cut">
                                                    <div class="pswp__preloader__donut"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                                        <div class="pswp__share-tooltip"></div>
                                    </div>

                                    <button class="pswp__button pswp__button--arrow--left"
                                            title="Previous (arrow left)">
                                    </button>

                                    <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)">
                                    </button>

                                    <div class="pswp__caption">
                                        <div class="pswp__caption__center"></div>
                                    </div>

                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('patient::patient.getbeforeafter')

        <div class="block my-block" id="my-block3">
            <div class="block-header block-header-default">
                <div class="d-flex" style="width: 100%">
                    <h3 class="block-title col-md-2">Record Mapping Wajah</h3>
                    <div class="col-md-8">
                        <input type="checkbox" id="is_record" name="is_record" class="switch-input">
                        <label for="is_record" class="switch-label"><span class="toggle--on">Ya</span><span
                                class="toggle--off">Tidak</span></label>
                    </div><!--col-->
                </div>
            </div>
            <div class="block-content block-content-full">
                <div class="row clearfix">
                    <div class="col-md-12" id="record_wajah_content" style="display: none">
                        <span id="error"></span>
                        <div class="col-lg-4">
                            <canvas id="c" width="710" height="300"></canvas>
                        </div>
                        <div class="d-flex p-4">
                            <table>
                                <tr>
                                    <td><img src="{{ asset('media/segitiga.png') }}" alt=""></td>
                                    <td><button type="button" class="btn btn-sm btn-outline-success" id="btnTanamBenang">Tanam Benang</button></td>
                                    <td><img src="{{ asset('media/little_oval.png') }}" alt=""></td>
                                    <td><button type="button" class="btn btn-sm btn-outline-success" id="btnFillerLittle">Filler</button></td>
                                </tr>
                                <tr>
                                    <td><img src="{{ asset('media/oval.png') }}" alt=""></td>
                                    <td><button type="button" class="btn btn-sm btn-outline-success" id="btnFillerOval">Filler</button></td>
                                    <td><img src="{{ asset('media/gel.png') }}" alt=""></td>
                                    <td><button type="button" class="btn btn-sm btn-outline-success" id="btnGelWajahMerata">Gel Wajah merata</button></td>
                                </tr>
                            </table>
                            &nbsp;
                            <button type="button" class="btn btn-sm btn-danger" style="display:none;" id="btnDelete">Hapus Komponen</button>
                            <button type="button" class="btn btn-sm btn-danger" id="btnSave">Btn Save</button>
                            <input type="text" id="base64" name="base64" hidden>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <div class="block my-block" id="block-inform">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Apakah membutuhkan SURAT PERSETUJUAN/PENOLAKAN MEDIS KHUSUS ?</h3>
                        <div class="block-options">
                            <label class="css-control css-control-primary css-checkbox">
                                <input type="checkbox" class="css-control-input" id="chcek-inform" name="chcek-inform">
                                <span class="css-control-indicator"></span> Centang Jika Ya
                            </label>
                        </div>
                    </div>
                    <div class="block-content block-content-full">
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label">Nama</label>
                            <div class="col-lg-7">
                                <div class="form-control-plaintext"><strong>{{ $patient->patient_name }}</strong></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label">Jenis Kelamin(L/P)</label>
                            <div class="col-lg-7">
                                <div class="form-control-plaintext">
                                    <strong>{{ ($patient->gender == 'F' ? 'Perempuan' : 'Laki - laki') }}</strong></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label">Umur</label>
                            <div class="col-lg-7">
                                <div class="form-control-plaintext"><strong>{{ $patient->age }}</strong></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label">Alamat</label>
                            <div class="col-lg-7">
                                <div class="form-control-plaintext"><strong>{{ $patient->address }}</strong></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label">Telp</label>
                            <div class="col-lg-7">
                                <div class="form-control-plaintext"><strong>{{ $patient->phone_number }}</strong></div>
                            </div>
                        </div>
                        <h5><small>Menyatakan dengan sesungguhnya dari saya sendiri/*sebagai orang
                                tua/*suami/*istri/*anak/*wali dari:</small></h5>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label">Nama</label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control" id="inform-name" name="inform-name">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label">Jenis Kelamin(L/P)</label>
                            <div class="col-lg-7">
                                {!! Form::select('inform-gender', ['M'=>'Laki - Laki','F'=>'Perempuan'], old('inform-gender'), ['class' => 'form-control', 'placeholder' => 'Pilih Jenis Kelamin']) !!}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label">Umur</label>
                            <div class="col-lg-7">
                                <div class="row">
                                    <div class="col-lg-2">
                                        <input type="number" class="form-control" id="inform-age" name="inform-age"
                                               value="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label">Alamat</label>
                            <div class="col-lg-7">
                                <input type="text" class="form-control" id="inform-address" name="inform-address">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label">Telp</label>
                            <div class="col-lg-7">
                                <input type="number" class="form-control" id="inform-phone" name="inform-phone">
                            </div>
                        </div>
                        <h5><small>Dengan ini menyatakan <strong>SETUJU/MENOLAK</strong> untuk dilakukan Tindakan Medis
                                berupa<input type="text" class="form-control" id="inform-notes"
                                             name="inform-notes"></small></h5>
                        <h5><small>Dari penjelasan yang diberikan, telah saya mengerti segala hal yang berhubungan
                                dengan penyakit tersebut, serta tindakan medis yang akan dilakukan dan kemungkinana
                                pasca tindakan yang dapat terjadi sesuai penjelasan yang diberikan.</small></h5>

                        <div class="row clearfix">
                            <div class="col-md-6 text-center">
                                <blockquote class="blockquote">
                                    <footer class="blockquote-footer">Dokter/Pelaksana</footer>
                                </blockquote>
                                <div id="signature" style="border-style: dotted;">
                                </div>
                                <blockquote class="blockquote">
                                    <footer class="blockquote-footer">{{ $logged_in_user->name }}</footer>
                                </blockquote>
                            </div>
                            <div class="col-md-6 text-center">
                                <blockquote class="blockquote">
                                    <footer class="blockquote-footer">Yang membuat pernyataan,</footer>
                                </blockquote>
                                <div id="signature2" style="border-style: dotted;">
                                </div>
                                <blockquote class="blockquote">
                                    <footer class="blockquote-footer">{{ $patient->patient_name }}</footer>
                                </blockquote>
                            </div>
                        </div>
                        <br>
                        <br>
                        <div class="row clearfix">
                            <div class="col-md-12 text-center">
                                <button type="button" onclick="$('#signature, #signature2').jSignature('clear')"
                                        class="btn btn-sm btn-warning mr-5 mb-5">
                                    <i class="fa fa-correct mr-5"></i>Reset Tandatangan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row clearfix">
            <div class="col-md-12 text-center">
                <button type="submit" class="btn btn-success"><span class="fa fa-check"></span> Submit</button>
            </div>
        </div>
    </form>
    <br>
    <br>
    <div class="block">
        <div class="block-header block-header-default">
            <h3 class="block-title">Histori</h3>
            <div class="block-options">
                <button type="button" class="btn-block-option" data-toggle="block-option"
                        data-action="fullscreen_toggle"></button>
                <button type="button" class="btn-block-option" data-toggle="block-option"
                        data-action="content_toggle"></button>
            </div>
        </div>
        <div class="block-content block-content-full">
            <ul class="list list-timeline list-timeline-modern pull-t">
                @foreach($patient->appointment as $key => $appointment)
                    <li>
                        <div class="list-timeline-time">{{ $appointment->appointment_no }}</div>
                        <i class="list-timeline-icon fa fa fa-stethoscope bg-info"></i>
                        <div class="list-timeline-content">
                            <p class="font-w600">{{ timezone()->convertToLocal($appointment->date, 'day') }}
                                , {{ timezone()->convertToLocal($appointment->date, 'date') }}
                                | {{ timezone()->convertToLocal($appointment->date, 'time') }}</p>
                            <p>Dokter: {{ $appointment->staff->user->full_name }}<br>
                                Ruangan: {{ isset($appointment->room->name) ? $appointment->room->name : '-' }}</p>
                            @if(isset($appointment->prescription))
                                <div class="row">
                                    <div class="col-sm-6 col-xl-4">
                                        <table class="table table-borderless table-striped">
                                            <thead>
                                            <th>#</th>
                                            <th>Resep</th>
                                            <th>Instruksi</th>
                                            </thead>
                                            <tbody>
                                            @foreach ($appointment->prescription->detail as $key => $item)
                                                <tr>
                                                    <td class="text-left">
                                                        <span class="text-muted">{{ ($key+1) }}</span>
                                                    </td>
                                                    <td>
                                                        <span class="text-muted font-w600">{{ $item->name }}</span>
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="text-muted font-w600">{{ $item->instruction }}</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    <!-- Pop Out Modal -->
    <div class="modal fade" id="modal-popout" tabindex="-1" role="dialog" aria-labelledby="modal-popout"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-popout" role="document">
            <div class="modal-content">
                <div class="block block-themed block-transparent mb-0">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title">Crop Gambar</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content">
                        <div id="upload-demo"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">Batalkan</button>
                    <button type="button" class="btn btn-alt-success" id="sipFoto">
                        <i class="fa fa-check"></i> Sip lah!
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- END Pop Out Modal -->

    <!-- Webcam -->
    <div class="modal fade" id="modal-webcam" tabindex="-1" role="dialog" aria-labelledby="modal-webcam"
         aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-popout" role="document">
            <div class="modal-content">
                <div class="block block-themed block-transparent mb-0">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title">Webcam</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="block text-center">
                                    <div class="block-content block-content-full block-content-sm">
                                        <div class="font-w600">Webcam</div>
                                    </div>
                                    <div class="block-content block-content-full bg-body-light">
                                        <div id="my_camera"></div>
                                    </div>
                                    <div class="block-content block-content-full">
                                        <button class="btn btn-white btn-sm btn-datatable-add blue" id="buttonStart"
                                                onclick="setup();">
                                            <i class="ace-icon fa fa-image"></i> Start
                                        </button>
                                        <button class="btn btn-white btn-sm btn-datatable-add blue" disabled="disabled"
                                                onclick="stop()" id="buttonStop">
                                            <i class="ace-icon fa fa-stop"></i> Stop
                                        </button>
                                        <button class="btn btn-white btn-sm btn-datatable-add blue" disabled="disabled"
                                                onclick="take_snapshot()" id="buttonSnap">
                                            <i class="ace-icon fa fa-camera"></i> Capture
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="block text-center">
                                    <div class="block-content block-content-full block-content-sm">
                                        <div class="font-w600">Hasil Foto</div>
                                    </div>
                                    <div class="block-content block-content-full bg-body-light">
                                        <div id="results"></div>
                                    </div>
                                    <div class="block-content block-content-full">
                                        <button type="button" class="btn btn-sm btn-success pull-right" id="savePhoto"
                                                onclick="post_to_preview()" disabled="disabled">
                                            <span class="bigger-110">Save Photo</span>

                                            <i class="ace-icon fa fa-arrow-right icon-on-right"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    <!-- END Webcam Modal -->
    <script src="{{ URL::asset('js/photoswipe.min.js') }}"></script>
    <script src="{{ URL::asset('js/photoswipe-ui-default.min.js') }}"></script>

    <script src="{{ URL::asset('js/plugins/jsignature/jSignature.js') }}"></script>

    <link rel="stylesheet" href="{{ URL::asset('js/plugins/croppie/croppie.css') }}">
    <script src="{{ URL::asset('js/plugins/croppie/croppie.js') }}"></script>
    <script src="{{ URL::asset('js/plugins/croppie/exif-js.js') }}"></script>
    <script src="{{ URL::asset('js/plugins/webcam/webcam.min.js') }}"></script>
    <script src="{{ asset('js/fabric.min.js') }}"></script>
    <script src="{{ asset('js/FileSaver.js') }}"></script>
    <script src="{{ asset('js/htmltoimage.js') }}"></script>
    <script language="JavaScript">
        Webcam.set({
            width: 320,
            height: 240,
            image_format: 'jpeg',
            jpeg_quality: 100
        });
    </script>
    <script type="text/javascript">
        jQuery(function () {

            Codebase.layout('sidebar_mini_on');
            Codebase.helpers(['masked-inputs', 'simplemde']);

            var dataString, datastring2;
            $("#signature").jSignature({
                color: "#00f",
                lineWidth: 3
            });
            $("#signature2").jSignature({
                color: "#00f",
                lineWidth: 3
            });

            $('#1diagnosis2, #product1, #service1').select2({
                placeholder: "Pilih"
            });

            $('.datepicker').datepicker({
                todayHighlight: true,
                format: "{{ setting()->get('date_format_js') }}",
                weekStart: 1,
                language: "{!! str_replace('_', '-', app()->getLocale()) !!}",
                daysOfWeekHighlighted: "0,6",
                autoclose: true
            });

            $(document).on("focusout", ".datepicker", function () {
                $(this).prop('readonly', false);
            });

            $(document).on("focusin", ".datepicker", function () {
                $(this).prop('readonly', true);
            });

            var i = 1;
            var j = 1;
            var k = 1;

            var validator = jQuery('.js-validation-bootstrap').validate({
                ignore: [],
                errorClass: 'invalid-feedback animated fadeInDown',
                errorElement: 'div',
                focusInvalid: true,
                /*errorPlacement: function(error, e) {
                    jQuery(e).closest('td').append(error);
                },*/
                invalidHandler: function (form, validator) {
                    var errors = validator.numberOfInvalids();
                    if (errors) {
                        var firstInvalidElement = $(validator.errorList[0].element);
                        $('html,body').scrollTop(firstInvalidElement.offset().top);
                        //firstInvalidElement.focus();
                    }
                },
                highlight: function (e) {
                    jQuery(e).closest('td').removeClass('is-invalid').addClass('is-invalid');
                },
                success: function (e) {
                    jQuery(e).closest('td').removeClass('is-invalid');
                    jQuery(e).remove();
                },
                checkForm: function () {
                    this.prepareForm();
                    for (var i = 0, elements = (this.currentElements = this.elements()); elements[i]; i++) {
                        if (this.findByName(elements[i].name).length != undefined && this.findByName(elements[i].name).length > 1) {
                            for (var cnt = 0; cnt < this.findByName(elements[i].name).length; cnt++) {
                                this.check(this.findByName(elements[i].name)[cnt]);
                            }
                        } else {
                            this.check(elements[i]);
                        }
                    }
                    return this.valid();
                },
                submitHandler: function (form) {
                    //Fetch Image
                    var formData = new FormData(form);

                    $('#imageGrid').find('img').each(function () {
                        imageName = $(this).attr('src');
                        formData.append('inputFoto[]', imageName);
                    });

                    if ($('#chcek-inform').is(':checked')) {

                        dataString = $("#signature").jSignature("getData");
                        dataString2 = $("#signature2").jSignature("getData");

                        /*if(dataString.isEmpty()){
                            $.alert({
                                title: 'Error',
                                icon: 'fa fa-warning',
                                type: 'red',
                                content: "Silahkan isi tandatangan!",
                            });

                            return false;
                        }
                        if(dataString2.isEmpty()){
                            $.alert({
                                title: 'Error',
                                icon: 'fa fa-warning',
                                type: 'red',
                                content: "Silahkan isi tandatangan!",
                            });

                            return false;
                        }*/

                        formData.append('withConcern', true);

                        formData.append('signature', dataString);
                        formData.append('signature2', dataString2);
                    } else {
                        formData.append('withConcern', false);
                    }

                    $.ajax({
                        type: "POST",
                        url: "{{ route('admin.patient.prescription.store') }}",
                        data: formData,
                        processData: false,
                        contentType: false,
                        dataType: 'json',
                        beforeSend: function (xhr) {
                            Codebase.blocks('.my-block', 'state_loading');
                        },
                        error: function (x, status, error) {
                            if (x.status == 403) {
                                $.alert({
                                    title: 'Error',
                                    icon: 'fa fa-warning',
                                    type: 'red',
                                    content: "Error: " + x.status + "",
                                });
                            } else if (x.status === 422) {
                                var errors = x.responseJSON;
                                var errorsHtml = '';
                                $.each(errors['errors'], function (index, value) {
                                    errorsHtml += '<ul><li class="text-danger">' + value + '</li></ul>';
                                });

                                $.alert({
                                    title: "Error " + x.status + ': ' + error,
                                    icon: 'fa fa-warning',
                                    type: 'red',
                                    content: errorsHtml,
                                    columnClass: 'col-md-5 col-md-offset-3',
                                    typeAnimated: true,
                                    draggable: false,
                                });

                            } else {
                                $.alert({
                                    title: 'Error',
                                    icon: 'fa fa-warning',
                                    type: 'red',
                                    content: "An error occurred: " + status + "nError: " + error,
                                });
                            }

                            Codebase.blocks('.my-block', 'state_normal');
                        },
                        success: function (result) {
                            if (result.status) {

                                Swal.fire({
                                    title: result.message,
                                    type: 'success',
                                    showCancelButton: false,
                                    showConfirmButton: true,
                                }).then((result) => {
                                    if (result.value) {
                                        window.location = '{{ route('admin.patient.appointment.index') }}';
                                    }
                                })
                            } else {

                                Swal.fire({
                                    title: result.message,
                                    type: 'error',
                                    showCancelButton: false,
                                    showConfirmButton: true,
                                })
                            }

                            Codebase.blocks('.my-block', 'state_normal');
                        }
                    });
                    return false; // required to block normal submit since you used ajax
                }

            });

            function updateIds() {
                $('#tab_logic tbody tr').each(function (i, element) {
                    var html = $(this).html();
                    if (html != '') {
                        $(this).find('.nomor').text((i + 1));
                        $(this).find('.product').attr('name', 'product[' + (i + 1) + ']');
                        $(this).find('.qty').attr('name', 'qty[' + (i + 1) + ']');
                        $(this).find('.instruction').attr('name', 'instruction[' + (i + 1) + ']');
                    }
                });

                validator.resetForm();
            }

            updateIds();

            function updateIds2() {
                $('#tab_logic2 tbody tr').each(function (j, element) {
                    var html2 = $(this).html();
                    if (html2 != '') {
                        $(this).find('.nomor2').text((j + 1));
                        $(this).find('.diagnosis').attr('name', 'diagnosis[' + (j + 1) + ']');
                        $(this).find('.diaginstruction').attr('name', 'diaginstruction[' + (j + 1) + ']');
                    }
                });

                validator.resetForm();
            }

            updateIds2();

            function updateIds3() {
                $('#tab_logic3 tbody tr').each(function (k, element) {
                    var html3 = $(this).html();
                    if (html3 != '') {
                        $(this).find('.nomor3').text((k + 1));
                        $(this).find('.service').attr('name', 'service[' + (k + 1) + ']');
                        $(this).find('.qty').attr('name', 'qty[' + (i + 1) + ']');
                        $(this).find('.servicenotes').attr('name', 'servicenotes[' + (k + 1) + ']');
                    }
                });

                validator.resetForm();
            }

            updateIds3();

            $(document).ready(function () {

                $(document).on('click', '.add', function () {
                    var html = '';
                    html += '<tr>';
                    html += '<td><button type="button" name="remove" class="btn btn-sm btn-circle btn-outline-danger remove" title="Hapus Item"><i class="fa fa-times"></i></button></td>';
                    html += '<td class="nomor">' + (i + 1) + '</td>';
                    html += '<td><select class="form-control product required" id="product' + (i + 1) + '" name="product[' + (i + 1) + ']" data-placeholder="Pilih" style="width: 100%">{!! $product !!}</select></td>';
                    html += '<td><input type="number" name="qty[' + (i + 1) + ']" class="form-control qty" value="1" min="1"/></td>';
                    html += '<td><input type="text" name="instruction[' + (i + 1) + ']" class="form-control instruction" /></td>';

                    html += '</tr>';

                    $('#tab_logic > tbody').append(html);

                    $('#product' + (i + 1) + '').select2({
                        placeholder: "Pilih"
                    });

                    i++;
                    updateIds();

                });

                $(document).on('click', '.remove', function () {
                    $(this).closest('tr').remove();
                    updateIds();
                });

                $(document).on('click', '.add2', function () {
                    var html2 = '';
                    html2 += '<tr>';
                    html2 += '<td><button type="button" name="remove2" class="btn btn-sm btn-circle btn-outline-danger remove2" title="Hapus Item"><i class="fa fa-times"></i></button></td>';
                    html2 += '<td class="nomor2">' + (j + 1) + '</td>';
                    html2 += '<td><select id="' + (j + 1) + 'diagnosis" name="diagnosis[' + (j + 1) + ']" class="form-control diagnosis required" data-placeholder="Pilih" style="width: 100%"><option></option>{!! $datadiagnoseitem !!}</select></td>';
                    html2 += '<td><input type="text" name="diaginstruction[' + (j + 1) + ']" class="form-control diaginstruction" /></td>';

                    html2 += '</tr>';

                    $('#tab_logic2 > tbody').append(html2);

                    $('#' + (j + 1) + 'diagnosis').select2({
                        placeholder: "Pilih"
                    });

                    j++;
                    updateIds2();
                });

                $(document).on('click', '.remove2', function () {
                    $(this).closest('tr').remove();
                    updateIds2();
                });

                $(document).on('click', '.add3', function () {
                    var html3 = '';
                    html3 += '<tr>';
                    html3 += '<td><button type="button" name="remove3" class="btn btn-sm btn-circle btn-outline-danger remove3" title="Hapus Item"><i class="fa fa-times"></i></button></td>';
                    html3 += '<td class="nomor3">' + (k + 1) + '</td>';
                    html3 += '<td><select class="form-control service required" id="service' + (k + 1) + '" name="service[' + (k + 1) + ']" data-placeholder="Pilih" style="width: 100%">{!! $service !!}</select></td>';
                    html3 += '<td><input type="number" name="qty[' + (i + 1) + ']" class="form-control qty" min="1" value="1"/></td>';
                    html3 += '<td><input type="text" name="servicenotes[' + (k + 1) + ']" class="form-control servicenotes" /></td>';

                    html3 += '</tr>';

                    $('#tab_logic3 > tbody').append(html3);

                    $('#service' + (k + 1) + '').select2({
                        placeholder: "Pilih"
                    });

                    k++;
                    updateIds3();
                });

                $(document).on('click', '.remove3', function () {
                    $(this).closest('tr').remove();
                    updateIds3();
                });
            });

            /*var imageNumber = 1;
            encodeImagetoBase64 = function (element) {

                var file = element.files[0];

                var reader = new FileReader();

                reader.onloadend = function() {
                    imageNumber++;
                    var templateImage = '<div class="col-md-2 animated fadeIn">'+
                                        '    <div class="options-container">'+
                                        '        <img id="inputFoto'+imageNumber+'" name="inputFoto[]" class="img-fluid options-item" src="'+reader.result+'" alt="">'+
                                        '        <div class="options-overlay bg-primary-dark-op">'+
                                        '            <div class="options-overlay-content">'+
                                        '                <a class="btn btn-sm btn-rounded btn-danger min-width-75" href="javascript:void(0)">'+
                                        '                    <i class="fa fa-times"></i> Delete'+
                                        '                </a>'+
                                        '            </div>'+
                                        '        </div>'+
                                        '    </div>'+
                                        '    '
                                        '</div>';
                                        alert('inputFoto'+imageNumber);
                    $("#imageGrid").append(templateImage);
                }

                reader.readAsDataURL(file);
                $("#file-input-foto").val("");

                $('#inputFoto'+imageNumber).croppie();
            }*/
            var imageNumber = 0;
            createImageGrid = function (image) {
                imageNumber++;
                var templateImage = '<div class="col-md-2 animated fadeIn" id="inputFoto' + imageNumber + '">\n' +
                    '    <div class="options-container">' +
                    '                            <figure itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject">\n' +
                    '                                <a href="' + image + '" itemprop="contentUrl" data-size="1024x1024">\n' +
                    '                                    <img name="inputFoto[]" src="' + image + '" itemprop="thumbnail" alt="Image description" />\n' +
                    '                                </a>\n' +
                    '                                <figcaption itemprop="caption description">Image caption  1</figcaption>\n' +
                    '\n' +
                    '                            </figure>\n' +
                    '    </div>' +
                    '<div class="row ml-sm-1"> ' +
                    '<input name="tipe[]" hidden>' +
                    '<button type="button" class="btn btn-sm btn-success mr-5 mb-5 btnBefore"  name="btnbefore">\n' +
                    '                            <i class="fa fa-plus mr-5"></i>Before\n' +
                    '                        </button>' +
                    '<button type="button" class="btn btn-sm btn-danger mr-5 mb-5 btnAfter" name="btnafter">\n' +
                    '                            <i class="fa fa-plus mr-5"></i>After\n' +
                    '                        </button>' +
                    '<button type="button" class="btn btn-sm btn-danger mr-5 mb-5 btnCancel" name="btncancel" style="display: none;">\n' +
                    '                            <i class="fa fa-plus mr-5"></i>Batalkan Dari \n' +
                    '                        </button>' +
                    ' </div>'
                '                        </div>'
                $("#imageGrid").append(templateImage);
                $("#file-input-foto").val("");

                var cls = $('.btnBefore');

                $.each(cls, function (index, value) {

                    if (index === (imageNumber - 1)) {
                        cls[index].click();
                    }
                })

            }

            $uploadCrop = $('#upload-demo').croppie({
                boundary: {
                    width: 400,
                    height: 400
                },
                viewport: {
                    width: 300,
                    height: 300,
                    type: 'square'
                },
                enableExif: true
            });

            $(document).on("change", "[type=file]", function () {
                var input = this
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $uploadCrop.croppie('bind', {
                            url: e.target.result
                        });
                        $(input).replaceWith($(input).clone())
                    }
                    reader.readAsDataURL(input.files[0]);

                    $('#modal-popout').modal('show');
                }
            })

            $('#modal-popout').on('shown.bs.modal', function () {
                $uploadCrop.croppie('bind');
                // $uploadCrop.croppie('setZoom', 1.0);
            });

            $('#modal-popout').on('hidden.bs.modal', function () {
                $("#file-input-foto").val("");
            });

            $("#sipFoto").on('click', function (e) {
                $uploadCrop.croppie('result', {
                    type: 'canvas',
                    size: 'viewport'
                }).then(function (response) {
                    createImageGrid(response);
                    $('#modal-popout').modal('hide');
                });
            })

            $('#imageGrid').on('click', '.deletePhoto', function () {
                $('#imageGrid').find('#' + $(this).data('image-id')).remove();
            });

            var avatar = "{{ asset('media/avatars/default.png') }}";

            default_avatar = function () {
                document.getElementById('results').innerHTML =
                    '<img width="240px" src="' + avatar + '"/>';
            }

            setup = function () {

                Webcam.reset();
                Webcam.on('error', function (err) {
                    Swal.fire({
                        title: 'Webcam tidak terhubung!',
                        type: 'error',
                        showCancelButton: false,
                        showConfirmButton: true,
                    })

                    $('#modal-webcam').modal('hide');
                });
                Webcam.attach('#my_camera');
                default_avatar();
                $('#buttonStart').prop('disabled', true);
                $('#buttonStop').prop('disabled', false);
                $('#buttonSnap').prop('disabled', false);
                $('#savePhoto').prop('disabled', true);
            }

            take_snapshot = function () {
                // take snapshot and get image data
                Webcam.snap(function (data_uri) {
                    // display results in page
                    document.getElementById('results').innerHTML =
                        '<img src="' + data_uri + '"/>';
                });

                $('#savePhoto').prop('disabled', false);
            }

            stop = function () {
                // Reset
                Webcam.reset();
                default_avatar();

                $('#buttonStart').prop('disabled', false);
                $('#buttonStop').prop('disabled', true);
                $('#buttonSnap').prop('disabled', true);
                $('#savePhoto').prop('disabled', true);
            }

            post_to_preview = function () {
                var imageResult = $('#modal-webcam').find("#results > img").attr("src");

                createImageGrid(imageResult);

                $('#modal-webcam').modal('hide');
            }

            $('#modal-webcam').on('shown.bs.modal', function (e) {
                setup();
            });

            $('#modal-webcam').on('hidden.bs.modal', function (e) {
                stop();
            });

            Codebase.blocks('#block-inform', 'content_hide');
            $('#chcek-inform').change(function () {
                if ($(this).is(":checked")) {
                    Codebase.blocks('#block-inform', 'content_show');
                } else {
                    Codebase.blocks('#block-inform', 'content_hide');
                }

            });

            var initPhotoSwipeFromDOM = function (gallerySelector) {

                // parse slide data (url, title, size ...) from DOM elements
                // (children of gallerySelector)
                var parseThumbnailElements = function (el) {
                    var thumbElements = el.childNodes,
                        numNodes = thumbElements.length,
                        items = [],
                        figureEl,
                        linkEl,
                        size,
                        item;

                    for (var i = 0; i < numNodes; i++) {

                        figureEl = thumbElements[i]; // <figure> element

                        // include only element nodes
                        if (figureEl.nodeType !== 1) {
                            continue;
                        }

                        linkEl = figureEl.children[0]; // <a> element

                        size = linkEl.getAttribute('data-size').split('x');

                        // create slide object
                        item = {
                            src: linkEl.getAttribute('href'),
                            w: parseInt(size[0], 10),
                            h: parseInt(size[1], 10)
                        };


                        if (figureEl.children.length > 1) {
                            // <figcaption> content
                            item.title = figureEl.children[1].innerHTML;
                        }

                        if (linkEl.children.length > 0) {
                            // <img> thumbnail element, retrieving thumbnail url
                            item.msrc = linkEl.children[0].getAttribute('src');
                        }

                        item.el = figureEl; // save link to element for getThumbBoundsFn
                        items.push(item);
                    }

                    return items;
                };

                // find nearest parent element
                var closest = function closest(el, fn) {
                    return el && (fn(el) ? el : closest(el.parentNode, fn));
                };

                // triggers when user clicks on thumbnail
                var onThumbnailsClick = function (e) {
                    e = e || window.event;
                    e.preventDefault ? e.preventDefault() : e.returnValue = false;

                    var eTarget = e.target || e.srcElement;

                    // find root element of slide
                    var clickedListItem = closest(eTarget, function (el) {
                        return (el.tagName && el.tagName.toUpperCase() === 'FIGURE');
                    });

                    if (!clickedListItem) {
                        return;
                    }

                    // find index of clicked item by looping through all child nodes
                    // alternatively, you may define index via data- attribute
                    var clickedGallery = clickedListItem.parentNode,
                        childNodes = clickedListItem.parentNode.childNodes,
                        numChildNodes = childNodes.length,
                        nodeIndex = 0,
                        index;

                    for (var i = 0; i < numChildNodes; i++) {
                        if (childNodes[i].nodeType !== 1) {
                            continue;
                        }

                        if (childNodes[i] === clickedListItem) {
                            index = nodeIndex;
                            break;
                        }
                        nodeIndex++;
                    }


                    if (index >= 0) {
                        // open PhotoSwipe if valid index found
                        openPhotoSwipe(index, clickedGallery);
                    }
                    return false;
                };

                // parse picture index and gallery index from URL (#&pid=1&gid=2)
                var photoswipeParseHash = function () {
                    var hash = window.location.hash.substring(1),
                        params = {};

                    if (hash.length < 5) {
                        return params;
                    }

                    var vars = hash.split('&');
                    for (var i = 0; i < vars.length; i++) {
                        if (!vars[i]) {
                            continue;
                        }
                        var pair = vars[i].split('=');
                        if (pair.length < 2) {
                            continue;
                        }
                        params[pair[0]] = pair[1];
                    }

                    if (params.gid) {
                        params.gid = parseInt(params.gid, 10);
                    }

                    return params;
                };

                var openPhotoSwipe = function (index, galleryElement, disableAnimation, fromURL) {
                    var pswpElement = document.querySelectorAll('.pswp')[0],
                        gallery,
                        options,
                        items;

                    items = parseThumbnailElements(galleryElement);

                    // define options (if needed)
                    options = {

                        // define gallery index (for URL)
                        galleryUID: galleryElement.getAttribute('data-pswp-uid'),

                        getThumbBoundsFn: function (index) {
                            // See Options -> getThumbBoundsFn section of documentation for more info
                            var thumbnail = items[index].el.getElementsByTagName('img')[0], // find thumbnail
                                pageYScroll = window.pageYOffset || document.documentElement.scrollTop,
                                rect = thumbnail.getBoundingClientRect();

                            return {x: rect.left, y: rect.top + pageYScroll, w: rect.width};
                        }

                    };

                    // PhotoSwipe opened from URL
                    if (fromURL) {
                        if (options.galleryPIDs) {
                            // parse real index when custom PIDs are used
                            // http://photoswipe.com/documentation/faq.html#custom-pid-in-url
                            for (var j = 0; j < items.length; j++) {
                                if (items[j].pid == index) {
                                    options.index = j;
                                    break;
                                }
                            }
                        } else {
                            // in URL indexes start from 1
                            options.index = parseInt(index, 10) - 1;
                        }
                    } else {
                        options.index = parseInt(index, 10);
                    }

                    // exit if index not found
                    if (isNaN(options.index)) {
                        return;
                    }

                    if (disableAnimation) {
                        options.showAnimationDuration = 0;
                    }

                    // Pass data to PhotoSwipe and initialize it
                    gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, items, options);
                    gallery.init();
                };

                // loop through all gallery elements and bind events
                var galleryElements = document.querySelectorAll(gallerySelector);

                for (var i = 0, l = galleryElements.length; i < l; i++) {
                    galleryElements[i].setAttribute('data-pswp-uid', i + 1);
                    galleryElements[i].onclick = onThumbnailsClick;
                }

                // Parse URL and open gallery if it contains #&pid=3&gid=1
                var hashData = photoswipeParseHash();
                if (hashData.pid && hashData.gid) {
                    openPhotoSwipe(hashData.pid, galleryElements[hashData.gid - 1], true, true);
                }
            };

// execute above function
            initPhotoSwipeFromDOM('.my-gallery');

            $('body').on('click', '.btnAfter', function (e) {
                var parent = $(this).parent();
                var btnCancel = parent.find('button[name=btncancel]')


                if (btnCancel.hasClass('btn-success')) {
                    btnCancel.removeClass('btn-success')
                }

                btnCancel.addClass('btn-danger')

                btnCancel.css('display', 'inline-block')
                btnCancel.html('Batalkan Dari After')

                var myButton = $(this);
                myButton.css('display', 'none');

                var btnBefore = parent.find('button[name=btnbefore]');
                btnBefore.css('display', 'none');

                parent.find('input').val("after")
            })

            $('body').on('click', '.btnCancel', function (e) {
                var parent = $(this).parent();

                var myButton = $(this);
                myButton.css('display', 'none');

                var btnBefore = parent.find('button[name=btnbefore]');
                btnBefore.css('display', 'inline-block');

                var btnAfter = parent.find('button[name=btnafter]');
                btnAfter.css('display', 'inline-block');

                parent.find('input').val("")

            })

            $('body').on('click', '.btnBefore', function (e) {
                var parent = $(this).parent();
                var btnCancel = parent.find('button[name=btncancel]');


                if (btnCancel.hasClass('btn-danger')) {
                    btnCancel.removeClass('btn-danger')
                }

                btnCancel.addClass('btn-success')

                btnCancel.css('display', 'inline-block')
                btnCancel.html('Batalkan Dari Before')

                var myButton = $(this);
                myButton.css('display', 'none');

                var btnAfter = parent.find('button[name=btnafter]');
                btnAfter.css('display', 'none');

                parent.find('input').val("before")
            })

            fabric.Object.prototype.set({
                transparentCorners: false,
                cornerColor: 'rgba(102,153,255,0.5)',
                cornerSize: 12,
                padding: 5
            });

            var canvas = window._canvas = new fabric.Canvas('c');

            canvas.on('mouse:up', function () {
                if(canvas.getActiveObject()) {
                    $("#btnDelete").show();
                } else {
                    $("#btnDelete").hide();
                }
            });

            canvas.setBackgroundImage('{{ asset('media/example.PNG') }}', canvas.renderAll.bind(canvas), {
                backgroundImageOpacity: 0.5,
                backgroundImageStretch: false,
            });


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

            $("#btnFillerLittle").on('click',function (e) {
                fabric.Image.fromURL('{{ asset('media/little_oval.png') }}',function (myImage) {
                    var img1 = myImage.set({ left: 0, top: 0 ,width:40,height:40});
                    canvas.add(img1)
                })
            })

            $("#btnGelWajahMerata").on('click',function (e) {
                fabric.Image.fromURL('{{ asset('media/gel.png') }}',function (myImage) {
                    var img1 = myImage.set({ left: 0, top: 0 ,width:40,height:40});
                    canvas.add(img1)
                })
            })

            $("#is_record").on('change',function (){
               if($("#is_record").is(':checked')) {
                   $("#record_wajah_content").show();
               } else {
                   $("#record_wajah_content").hide();
               }
            })

            $("#btnDelete").on('click',function () {
                var object = canvas.getActiveObject()
                if (!object){
                    alert('Please select the element to remove');
                    return '';
                }
                canvas.remove(object);
                $(this).hide()
            })

            $("#btnSave").on('click',function () {
                console.log(JSON.stringify(canvas))


                var can = document.getElementById("c")
                var base64 = can.toDataURL();
                $("#base64").val(base64)
            })

        });
    </script>
@endsection
